<?php
// app/Helpers/Mailer_helper.php

use App\Models\Document;
use App\Models\User;

/**
 * We remove "namespace App\Helpers;" so these functions
 * become globally available in your application.
 */

if (!class_exists('MailerLogic')) {
    /**
     * Private internal logic handler for this helper, mirroring the
     * LoggerLogic pattern used by Logger_helper.php.
     */
    class MailerLogic
    {
        private static function typeMeta(string $type): array
        {
            return match ($type) {
                'graduate_thesis' => ['label' => 'Graduate Thesis', 'path' => 'documents/graduateThesis/view/'],
                'dissertation'    => ['label' => 'Dissertation', 'path' => 'documents/dissertations/view/'],
                'faculty_research' => ['label' => 'Faculty Research', 'path' => 'documents/facultyResearch/view/'],
                default           => ['label' => 'Document', 'path' => 'documents/viewDocument/'],
            };
        }

        public static function fullName(?array $user): string
        {
            if (!$user) {
                return '';
            }

            $name = ($user['first_name'] ?? '') . ' ' . ($user['middle_name'] ?? '') . ' ' . ($user['last_name'] ?? '') . ' ' . ($user['suffix'] ?? '');
            return trim(preg_replace('/\s+/', ' ', $name));
        }

        public static function loadContext(int $documentId): ?array
        {
            $documentModel = new Document();
            $userModel = new User();

            $document = $documentModel->find($documentId);
            if (!$document) {
                return null;
            }

            $student = $document['user_id'] ? $userModel->find($document['user_id']) : null;
            $adviser = $document['adviser_id'] ? $userModel->find($document['adviser_id']) : null;
            $meta = self::typeMeta($document['type']);

            helper('url');

            return [
                'document'     => $document,
                'student'      => $student,
                'studentName'  => self::fullName($student) ?: 'Student',
                'adviser'      => $adviser,
                'adviserName'  => self::fullName($adviser) ?: 'Adviser',
                'typeLabel'    => $meta['label'],
                'viewUrl'      => base_url($meta['path'] . $document['id']),
            ];
        }

        public static function librarians(): array
        {
            $userModel = new User();
            return $userModel->where('user_level', 'librarian')->findAll();
        }

        private static function renderTemplate(string $heading, string $bodyHtml, string $ctaUrl, string $ctaLabel): string
        {
            return '
            <div style="font-family:Verdana,Geneva,sans-serif;background-color:#f4f4f4;padding:24px;">
                <div style="max-width:560px;margin:0 auto;background:#ffffff;border-radius:6px;overflow:hidden;border:1px solid #e0e0e0;">
                    <div style="background-color:#a80000;color:#ffffff;padding:18px 24px;">
                        <h2 style="margin:0;font-size:18px;">Thesis Repository</h2>
                    </div>
                    <div style="padding:24px;color:#333333;font-size:14px;line-height:1.6;">
                        <h3 style="margin-top:0;color:#a80000;">' . esc($heading) . '</h3>
                        ' . $bodyHtml . '
                        <p style="text-align:center;margin:28px 0;">
                            <a href="' . esc($ctaUrl) . '" style="background-color:#a80000;color:#ffffff;text-decoration:none;padding:12px 28px;border-radius:4px;font-weight:bold;display:inline-block;">' . esc($ctaLabel) . '</a>
                        </p>
                    </div>
                    <div style="background-color:#f4f4f4;color:#888888;font-size:12px;text-align:center;padding:12px;">
                        This is an automated message from the Thesis Repository System. Please do not reply to this email.
                    </div>
                </div>
            </div>';
        }

        public static function send(?string $toEmail, ?string $toName, string $heading, string $subject, string $bodyHtml, string $ctaUrl, string $ctaLabel = 'View Document'): bool
        {
            if (empty($toEmail)) {
                return false;
            }

            try {
                $config = config('Email');
                $fromEmail = $config->fromEmail !== '' ? $config->fromEmail : $config->SMTPUser;
                $fromName = $config->fromName !== '' ? $config->fromName : 'Thesis Repository';

                $email = \Config\Services::email();
                $email->setFrom($fromEmail, $fromName);
                $email->setTo($toEmail, $toName ?: '');
                $email->setSubject($subject);
                $email->setMessage(self::renderTemplate($heading, $bodyHtml, $ctaUrl, $ctaLabel));
                $email->setMailType('html');
 
                if (!$email->send()) {
                    log_message('error', 'Mailer: failed to send "' . $subject . '" to ' . $toEmail . ' - ' . $email->printDebugger(['headers']));
                    return false;
                }

                return true;
            } catch (\Throwable $e) {
                log_message('error', 'Mailer Exception: ' . $e->getMessage());
                return false;
            }
        }
    }
}

/**
 * Student submits a new thesis/dissertation/faculty research.
 * Notifies: the student (confirmation) and the selected adviser.
 */
if (!function_exists('notifyThesisSubmitted')) {
    function notifyThesisSubmitted(int $documentId): void
    {
        $ctx = MailerLogic::loadContext($documentId);
        if (!$ctx) {
            return;
        }

        $document = $ctx['document'];
        $title = esc($document['title']);

        if (!empty($ctx['student']['email'])) {
            $body = '<p>Hi ' . esc($ctx['studentName']) . ',</p>
                <p>Your ' . esc($ctx['typeLabel']) . ' titled <strong>"' . $title . '"</strong> has been submitted successfully and is now awaiting review by ' . esc($ctx['adviserName']) . '.</p>';
            MailerLogic::send($ctx['student']['email'], $ctx['studentName'], 'Submission Received', 'Thesis Submitted Successfully', $body, $ctx['viewUrl']);
        }

        if (!empty($ctx['adviser']['email'])) {
            $body = '<p>Hi ' . esc($ctx['adviserName']) . ',</p>
                <p><strong>' . esc($ctx['studentName']) . '</strong> has submitted a ' . esc($ctx['typeLabel']) . ' titled <strong>"' . $title . '"</strong> for your review.</p>';
            MailerLogic::send($ctx['adviser']['email'], $ctx['adviserName'], 'New Submission for Review', 'New Thesis Submission Awaiting Your Review', $body, $ctx['viewUrl'], 'Review Document');
        }
    }
}

/**
 * Adviser endorses or sends a document back for revision.
 * Notifies: the student and every librarian account.
 */
if (!function_exists('notifyAdviserDecision')) {
    function notifyAdviserDecision(int $documentId, string $status, ?string $remarks = null): void
    {
        if (!in_array($status, ['endorsed', 'revise'], true)) {
            return;
        }

        $ctx = MailerLogic::loadContext($documentId);
        if (!$ctx) {
            return;
        }

        $document = $ctx['document'];
        $title = esc($document['title']);
        $remarksHtml = $remarks ? '<p><strong>Adviser remarks:</strong> ' . esc($remarks) . '</p>' : '';

        if ($status === 'endorsed') {
            if (!empty($ctx['student']['email'])) {
                $body = '<p>Hi ' . esc($ctx['studentName']) . ',</p>
                    <p>Your ' . esc($ctx['typeLabel']) . ' titled <strong>"' . $title . '"</strong> has been endorsed by ' . esc($ctx['adviserName']) . ' and forwarded to the library for publishing.</p>' . $remarksHtml;
                MailerLogic::send($ctx['student']['email'], $ctx['studentName'], 'Thesis Endorsed', 'Your Thesis Has Been Endorsed', $body, $ctx['viewUrl']);
            }

            foreach (MailerLogic::librarians() as $librarian) {
                if (empty($librarian['email'])) {
                    continue;
                }
                $librarianName = trim(($librarian['first_name'] ?? '') . ' ' . ($librarian['last_name'] ?? '')) ?: 'Librarian';
                $body = '<p>Hi ' . esc($librarianName) . ',</p>
                    <p>The ' . esc($ctx['typeLabel']) . ' titled <strong>"' . $title . '"</strong> by ' . esc($ctx['studentName']) . ' has been endorsed by ' . esc($ctx['adviserName']) . ' and is ready for review/publishing.</p>';
                MailerLogic::send($librarian['email'], $librarianName, 'Thesis Ready for Publishing', 'Thesis Endorsed - Ready for Publishing', $body, $ctx['viewUrl'], 'Review Document');
            }
        } else { // revise
            if (!empty($ctx['student']['email'])) {
                $body = '<p>Hi ' . esc($ctx['studentName']) . ',</p>
                    <p>' . esc($ctx['adviserName']) . ' has requested revisions for your ' . esc($ctx['typeLabel']) . ' titled <strong>"' . $title . '"</strong>. Please review the feedback and resubmit.</p>' . $remarksHtml;
                MailerLogic::send($ctx['student']['email'], $ctx['studentName'], 'Revisions Requested', 'Revisions Requested for Your Thesis', $body, $ctx['viewUrl'], 'View Feedback');
            }

            foreach (MailerLogic::librarians() as $librarian) {
                if (empty($librarian['email'])) {
                    continue;
                }
                $librarianName = trim(($librarian['first_name'] ?? '') . ' ' . ($librarian['last_name'] ?? '')) ?: 'Librarian';
                $body = '<p>Hi ' . esc($librarianName) . ',</p>
                    <p>FYI: ' . esc($ctx['adviserName']) . ' has sent the ' . esc($ctx['typeLabel']) . ' titled <strong>"' . $title . '"</strong> by ' . esc($ctx['studentName']) . ' back to the student for revisions.</p>' . $remarksHtml;
                MailerLogic::send($librarian['email'], $librarianName, 'Thesis Sent for Revision', 'Thesis Sent Back for Revision by Adviser', $body, $ctx['viewUrl']);
            }
        }
    }
}

/**
 * Librarian publishes or sends a document back for revision.
 * Notifies: the student and the adviser.
 */
if (!function_exists('notifyLibrarianDecision')) {
    function notifyLibrarianDecision(int $documentId, string $status, ?string $remarks = null): void
    {
        if (!in_array($status, ['published', 'revise'], true)) {
            return;
        }

        $ctx = MailerLogic::loadContext($documentId);
        if (!$ctx) {
            return;
        }

        $document = $ctx['document'];
        $title = esc($document['title']);
        $remarksHtml = $remarks ? '<p><strong>Library remarks:</strong> ' . esc($remarks) . '</p>' : '';

        if ($status === 'published') {
            if (!empty($ctx['student']['email'])) {
                $body = '<p>Hi ' . esc($ctx['studentName']) . ',</p>
                    <p>Congratulations! Your ' . esc($ctx['typeLabel']) . ' titled <strong>"' . $title . '"</strong> has been published in the library.</p>';
                MailerLogic::send($ctx['student']['email'], $ctx['studentName'], 'Thesis Published', 'Your Thesis Has Been Published', $body, $ctx['viewUrl']);
            }

            if (!empty($ctx['adviser']['email'])) {
                $body = '<p>Hi ' . esc($ctx['adviserName']) . ',</p>
                    <p>The ' . esc($ctx['typeLabel']) . ' titled <strong>"' . $title . '"</strong> by ' . esc($ctx['studentName']) . ', which you endorsed, has now been published.</p>';
                MailerLogic::send($ctx['adviser']['email'], $ctx['adviserName'], 'Thesis Published', 'Thesis You Endorsed Has Been Published', $body, $ctx['viewUrl']);
            }
        } else { // revise
            if (!empty($ctx['student']['email'])) {
                $body = '<p>Hi ' . esc($ctx['studentName']) . ',</p>
                    <p>The library has requested revisions for your ' . esc($ctx['typeLabel']) . ' titled <strong>"' . $title . '"</strong>. Please review the feedback and resubmit.</p>' . $remarksHtml;
                MailerLogic::send($ctx['student']['email'], $ctx['studentName'], 'Revisions Requested', 'Library Has Requested Revisions for Your Thesis', $body, $ctx['viewUrl'], 'View Feedback');
            }

            if (!empty($ctx['adviser']['email'])) {
                $body = '<p>Hi ' . esc($ctx['adviserName']) . ',</p>
                    <p>FYI: the library has sent the ' . esc($ctx['typeLabel']) . ' titled <strong>"' . $title . '"</strong> by ' . esc($ctx['studentName']) . ' back for revisions.</p>' . $remarksHtml;
                MailerLogic::send($ctx['adviser']['email'], $ctx['adviserName'], 'Thesis Sent for Revision', 'Thesis Sent Back for Revision by Library', $body, $ctx['viewUrl']);
            }
        }
    }
}

/**
 * Student resubmits a previously revised document.
 * Notifies: the adviser only.
 */
if (!function_exists('notifyStudentResubmission')) {
    function notifyStudentResubmission(int $documentId, ?string $remarks = null): void
    {
        $ctx = MailerLogic::loadContext($documentId);
        if (!$ctx || empty($ctx['adviser']['email'])) {
            return;
        }

        $document = $ctx['document'];
        $title = esc($document['title']);
        $remarksHtml = $remarks ? '<p><strong>Student remarks:</strong> ' . esc($remarks) . '</p>' : '';

        $body = '<p>Hi ' . esc($ctx['adviserName']) . ',</p>
            <p>' . esc($ctx['studentName']) . ' has resubmitted the revised ' . esc($ctx['typeLabel']) . ' titled <strong>"' . $title . '"</strong> for your review.</p>' . $remarksHtml;

        MailerLogic::send($ctx['adviser']['email'], $ctx['adviserName'], 'Thesis Resubmitted', 'Revised Thesis Resubmitted for Your Review', $body, $ctx['viewUrl'], 'Review Document');
    }
}

/**
 * A new account is registered.
 * Notifies: the new user, telling them their account is inactive until an
 * administrator or librarian activates it.
 */
if (!function_exists('notifyUserRegistered')) {
    function notifyUserRegistered(int $userId): void
    {
        helper('url');

        $userModel = new User();
        $user = $userModel->find($userId);

        if (!$user || empty($user['email'])) {
            return;
        }

        $name = MailerLogic::fullName($user) ?: 'there';

        $body = '<p>Hi ' . esc($name) . ',</p>
            <p>An account has been created for you in the Thesis Repository System using this email address.</p>
            <p>Your account is currently <strong>inactive</strong> and cannot be used to log in yet. Please contact the system administrator or your school librarian to have your account activated.</p>';

        MailerLogic::send($user['email'], $name, 'Account Created', 'Your Thesis Repository Account Has Been Created', $body, base_url('login'), 'Go to Login');
    }
}
