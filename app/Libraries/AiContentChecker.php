<?php

namespace App\Libraries;

use Smalot\PdfParser\Parser as PdfParser;

class AiContentChecker
{
    private const MAX_CHAPTERS = 12;
    private const CHARS_PER_CHAPTER = 3000;
    private const PASS_THRESHOLD = 70;

    // Retried on 429/502/503/504 only — these are transient (the provider asks
    // callers to "retry shortly"), unlike 401/400 which won't fix themselves.
    private const RETRYABLE_STATUS_CODES = [429, 502, 503, 504];
    private const MAX_ATTEMPTS = 2;
    private const RETRY_DELAY_SECONDS = 3;

    private const SYSTEM_PROMPT = <<<'PROMPT'
You are an academic writing analyst helping a university thesis library spot chapters that read as AI-generated rather than student-written.

For each chapter excerpt you are given, return a "humanness score" from 0 to 100:
- 100 means the writing clearly reads as natural, specific, human academic writing (varied sentence structure, concrete detail, occasional imperfection).
- 0 means the writing clearly reads as generic AI output (uniform sentence rhythm, generic transitions like "in conclusion" or "it is important to note", padded phrasing, lack of specific detail).

Respond with STRICT JSON ONLY, no markdown fences, no commentary, in exactly this shape:
{"chapters":[{"title":"<chapter title as given>","score":<integer 0-100>,"note":"<advice, max 25 words, empty string if score >= 70>"}]}

The chapters array must have exactly one entry per chapter excerpt provided, in the same order.
PROMPT;

    /**
     * @throws \RuntimeException on extraction or API failure
     */
    public function checkFile(string $filePath): array
    {
        $text = $this->extractText($filePath);

        if (trim($text) === '') {
            throw new \RuntimeException('Could not read any text from this PDF. It may be a scanned image without selectable text.');
        }

        $chapters = $this->splitChapters($text);
        $analyzed = $this->analyze($chapters);

        $overall = $analyzed === []
            ? 0
            : (int) round(array_sum(array_column($analyzed, 'score')) / count($analyzed));

        return [
            'overall'   => $overall,
            'passed'    => $overall >= self::PASS_THRESHOLD,
            'threshold' => self::PASS_THRESHOLD,
            'chapters'  => $analyzed,
        ];
    }

    private function extractText(string $filePath): string
    {
        try {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($filePath);

            return $pdf->getText();
        } catch (\Throwable $e) {
            throw new \RuntimeException('Could not read this PDF file: ' . $e->getMessage());
        }
    }

    /**
     * Splits extracted text into chapters using "Chapter N" style headings.
     * Falls back to treating the whole document as one chapter.
     */
    private function splitChapters(string $text): array
    {
        $text = preg_replace("/\r\n|\r/", "\n", $text);
        $pattern = '/^[ \t]*chapter\s+([0-9]+|[ivxlcdm]+)\b[^\n]*/im';

        if (!preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE)) {
            return [
                ['title' => 'Full Document', 'text' => $this->sample($text)],
            ];
        }

        $chapters = [];
        $count = min(count($matches[0]), self::MAX_CHAPTERS);

        for ($i = 0; $i < $count; $i++) {
            $heading = trim($matches[0][$i][0]);
            $start = $matches[0][$i][1];
            $end = ($i + 1 < count($matches[0])) ? $matches[0][$i + 1][1] : strlen($text);
            $body = substr($text, $start, $end - $start);
            $body = preg_replace('/^[^\n]*\n/', '', $body, 1);

            $chapters[] = [
                'title' => $heading !== '' ? $heading : ('Chapter ' . ($i + 1)),
                'text'  => $this->sample($body),
            ];
        }

        return $chapters;
    }

    private function sample(string $text): string
    {
        $text = trim(preg_replace('/[ \t]+/', ' ', $text));

        return mb_substr($text, 0, self::CHARS_PER_CHAPTER);
    }

    private function analyze(array $chapters): array
    {
        $content = $this->callOpenRouter($chapters);

        return $this->parseResponse($content, $chapters);
    }

    private function callOpenRouter(array $chapters): string
    {
        $apiKey = env('OPENROUTER_API_KEY');

        if (empty($apiKey)) {
            throw new \RuntimeException('AI check is not configured yet (missing OPENROUTER_API_KEY).');
        }

        $model = env('OPENROUTER_MODEL');

        $userContent = '';
        foreach ($chapters as $i => $chapter) {
            $userContent .= "\n\n### Chapter " . ($i + 1) . ': ' . $chapter['title'] . "\n" . $chapter['text'];
        }

        $payload = [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
                'HTTP-Referer'  => base_url(),
                'X-Title'       => 'Thesis Repository AI Content Check',
            ],
            'json' => [
                'model'       => $model,
                'temperature' => 0,
                'messages'    => [
                    ['role' => 'system', 'content' => self::SYSTEM_PROMPT],
                    ['role' => 'user', 'content' => $userContent],
                ],
            ],
            'http_errors' => false,
            'timeout'     => 60,
        ];

        for ($attempt = 1; $attempt <= self::MAX_ATTEMPTS; $attempt++) {
            try {
                $client = \Config\Services::curlrequest();
                $response = $client->post('https://openrouter.ai/api/v1/chat/completions', $payload);
            } catch (\Throwable $e) {
                throw new \RuntimeException('Could not reach the AI check service: ' . $e->getMessage());
            }

            $status = $response->getStatusCode();
            $body = (string) $response->getBody();

            if ($status >= 200 && $status < 300) {
                $decoded = json_decode($body, true);
                $content = $decoded['choices'][0]['message']['content'] ?? null;

                if (!$content) {
                    throw new \RuntimeException('The AI check service returned an unexpected response.');
                }

                return $content;
            }

            log_message('error', 'AiContentChecker: OpenRouter HTTP ' . $status . ' (attempt ' . $attempt . '/' . self::MAX_ATTEMPTS . ') - ' . $body);

            $isRetryable = in_array($status, self::RETRYABLE_STATUS_CODES, true);

            if (!$isRetryable || $attempt === self::MAX_ATTEMPTS) {
                throw new \RuntimeException($this->describeError($status, $body));
            }

            sleep(self::RETRY_DELAY_SECONDS);
        }

        // Unreachable, but keeps static analysis happy about the return type.
        throw new \RuntimeException('The AI check service returned an error. Please try again later.');
    }

    private function describeError(int $status, string $body): string
    {
        $decoded = json_decode($body, true);
        $message = $decoded['error']['message'] ?? null;
        // OpenRouter often wraps the actually-useful detail in error.metadata.raw
        // (e.g. free-model rate limits) behind a generic error.message like
        // "Provider returned error" — check both.
        $detail = $decoded['error']['metadata']['raw'] ?? $message;

        if ($status === 401) {
            return 'The AI check service rejected the configured API key. Check OPENROUTER_API_KEY in .env.';
        }

        if ($status === 429) {
            $isFreeModelLimit = is_string($detail) && str_contains($detail, 'rate-limited upstream');

            return $isFreeModelLimit
                ? 'The selected AI model is a free-tier model and is temporarily rate-limited by the provider. Try again in a minute, or switch OPENROUTER_MODEL in .env to a non-free model for more reliable results.'
                : 'The AI check service is rate-limiting requests right now. Please try again shortly.';
        }

        if ($status >= 500) {
            return 'The AI check service is temporarily unavailable. Please try again shortly.';
        }

        return 'The AI check service returned an error' . (is_string($message) ? (': ' . $message) : '') . '.';
    }

    private function parseResponse(string $content, array $chapters): array
    {
        $content = trim($content);
        $content = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', $content);

        $decoded = json_decode($content, true);

        if (!is_array($decoded) || empty($decoded['chapters']) || !is_array($decoded['chapters'])) {
            if (preg_match('/\{.*\}/s', $content, $m)) {
                $decoded = json_decode($m[0], true);
            }
        }

        if (!is_array($decoded) || empty($decoded['chapters']) || !is_array($decoded['chapters'])) {
            throw new \RuntimeException('Could not parse the AI check result. Please try again.');
        }

        $result = [];
        foreach ($decoded['chapters'] as $i => $entry) {
            $score = isset($entry['score']) ? (int) max(0, min(100, (int) $entry['score'])) : 0;

            $result[] = [
                'title'  => $entry['title'] ?? ($chapters[$i]['title'] ?? ('Chapter ' . ($i + 1))),
                'score'  => $score,
                'passed' => $score >= self::PASS_THRESHOLD,
                'note'   => trim((string) ($entry['note'] ?? '')),
            ];
        }

        return $result;
    }
}
