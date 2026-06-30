# Email Notifications & AI Content Check

Documentation for two features added on top of the base thesis repository system: automatic email notifications across the submission workflow, and an AI-generated-content checker for uploaded theses. Both are off by default until the `.env` keys below are filled in with real credentials.

---

## 1. Email Notifications

Sends HTML emails at each step of the thesis review workflow, for Graduate Thesis, Dissertation, and Faculty Research alike.

| Event | Triggered in | Notifies |
|---|---|---|
| Student submits a new thesis | `Graduates::insertGraduateThesis()`, `Dissertations::insertDissertations()`, `FacultyResearch::insertFacultyResearch()` | Student (confirmation) + selected adviser |
| Adviser endorses or sends back for revision | `*::edit()`, `action=update`, actor is the adviser | Student + every account with `user_level = librarian` |
| Librarian publishes or sends back for revision | `*::edit()`, `action=update`, actor is the librarian | Student + adviser |
| Student resubmits after a revision | `*::edit()`, `action=edit`, when prior status was `revise` | Adviser |
| New account is registered | `Auth::registerPost()` | The new user — tells them their account is inactive and to contact the administrator/librarian to activate it |

**Files:**
- [app/Helpers/Mailer_helper.php](app/Helpers/Mailer_helper.php) — global `notifyThesisSubmitted()`, `notifyAdviserDecision()`, `notifyLibrarianDecision()`, `notifyStudentResubmission()` functions, plus the branded HTML email template.
- [app/Config/Email.php](app/Config/Email.php) — SMTP defaults, pulls the Gmail App Password from `APP_PASSWORD`.
- Registered in [app/Controllers/BaseController.php](app/Controllers/BaseController.php) `$helpers` array, so it's available everywhere.

**Required `.env` keys:**

```ini
email.protocol = smtp
email.SMTPHost = smtp.gmail.com
email.SMTPUser = 'your-gmail-address@gmail.com'
email.SMTPPort = 587
email.SMTPCrypto = tls
email.fromEmail = 'your-gmail-address@gmail.com'
email.fromName = 'Thesis Repository'

APP_PASSWORD = 'your-16-character-gmail-app-password'
```

`APP_PASSWORD` must be a Gmail **App Password** (Google Account → Security → 2-Step Verification → App passwords), not your normal account password — Gmail SMTP will reject a regular password.

**Failure handling:** every send is wrapped in try/catch and logged via `log_message('error', ...)`. A failed email never blocks the document action it's attached to — the student/adviser/librarian action still succeeds even if Gmail rejects the message.

**Testing status:** the registration notification was confirmed live — called directly against a real account, sent without any error in the application log (Gmail SMTP accepted it). The other 3 workflow events (submit/endorse/publish/revise) are wired in and boot without errors but haven't had the same live confirmation yet.

---

## 2. AI Content Check

A manual, per-chapter check that estimates how "human-written" vs. AI-generated the text in an uploaded PDF reads, using an LLM via [OpenRouter](https://openrouter.ai).

**Where it appears:** a "Check AI Content" button next to every `thesis_file` input — on the initial upload forms (Graduate Thesis, Dissertation, Faculty Research) and on the revise/resubmit file field on each document's view page. The check is **manual** (click-triggered, not automatic on file selection) and **advisory only** — a failing score does not block form submission.

**How scoring works:**
1. Text is extracted from the PDF (`smalot/pdfparser`).
2. The text is split into chapters by detecting `Chapter N` / `Chapter IV` style headings; if none are found, the whole document is treated as one chapter ("Full Document").
3. All chapters are sent in a single OpenRouter chat-completion call, asking the model for a 0–100 "humanness score" per chapter.
4. **Score ≥ 70 → Passed** (green). **Score < 70 → Needs Revision** (red, with a short suggestion note from the model).
5. The overall badge shows the average across chapters; the per-chapter breakdown is shown in a collapsible "Details" panel beneath it.

**Files:**
- [app/Libraries/AiContentChecker.php](app/Libraries/AiContentChecker.php) — PDF extraction, chapter splitting, OpenRouter call (with retry-on-transient-error), response parsing.
- [app/Controllers/Documents.php](app/Controllers/Documents.php) `checkAiContent()` — validates the upload (real PDF via mime-sniffing, ≤10MB), rate-limits, and proxies to the checker.
- Route: `POST documents/checkAiContent` in [app/Config/Routes.php](app/Config/Routes.php).
- Frontend: shared JS in [app/Views/template/footer.php](app/Views/template/footer.php), markup in each form's view file.

**Required `.env` keys:**

```ini
OPENROUTER_API_KEY = 'your-openrouter-api-key'
OPENROUTER_MODEL = 'openai/gpt-4o-mini'
```

Get a key at [openrouter.ai/keys](https://openrouter.ai/keys). **Avoid `:free`-suffixed models** (e.g. `openai/gpt-oss-120b:free`) for anything beyond casual testing — free models share a rate-limit pool across every OpenRouter user, so requests fail with `429` under load that has nothing to do with your own usage. A cheap paid model (the default, `openai/gpt-4o-mini`) is far more reliable.

**Resilience:** transient errors (`429`/`502`/`503`/`504`) are retried once automatically with a short delay before surfacing an error. The error message is specific to the failure (bad API key vs. rate-limited vs. service down) rather than one generic line.

**Testing status:** the security and validation layer (see below) was verified end-to-end against a real authenticated session. A fully successful scored result has not yet been observed, since test attempts so far have hit either an invalid test key or the free model's shared rate limit — worth doing one real check with a paid model to confirm the happy-path UI.

---

## 3. Login Brute-Force Lockout

`Auth::loginPost()` tracks failed login attempts per email. **10 failed attempts within a 5-minute window locks the account** (`users.status = 0`) — the same inactive state used for unactivated registrations, so it reuses the existing "contact the administrator or librarian" flow rather than adding a new account state.

- Counting is cache-based (CodeIgniter's file cache, same mechanism as the AI-check rate limiter), keyed by the submitted email. No database schema change. The window resets 5 minutes after the *last* attempt, not a fixed clock — so an attacker spreading attempts out doesn't dodge the lock as long as they keep trying.
- Both "no such email" and "wrong password" count as a failed attempt against the submitted email, to avoid the failure mode where only known-valid emails get this protection.
- A successful login clears the counter for that email.
- Once locked, **even the correct password is rejected** until an admin reactivates the account (`Users::edit()`, the existing status toggle).
- Every failed attempt and lockout is recorded via `logAction()` — `LOGIN_FAILED` and `ACCOUNT_LOCKED` (the former already had a badge color defined in `Logger_helper.php` but was never actually triggered before this).

**Files:**
- [app/Controllers/Auth.php](app/Controllers/Auth.php) — `loginPost()`, `registerFailedLoginAttempt()`, `clearFailedLoginAttempts()`.
- [app/Helpers/Logger_helper.php](app/Helpers/Logger_helper.php) — added `ACCOUNT_LOCKED` badge mapping.

**Testing status:** verified live end-to-end against a real account — 9 wrong attempts left it active, the 10th flipped `status` to `0`, a *correct* password was then still rejected with the inactive message, and the account was restored and confirmed working again afterward. Confirmed in the `logs` table that both `LOGIN_FAILED` (with the attempt number) and `ACCOUNT_LOCKED` are recorded with the correct `resource_id`.

---

## 4. Security Measures

Specific to the AI Content Check endpoint, since it's the one that costs real money per call and is a new attack surface:

| Measure | What it does |
|---|---|
| **Rate limiting** | 5 requests per 10 minutes per logged-in user, via CodeIgniter's built-in `Throttler` (file-cache backed, no extra service needed). Returns `429` + `Retry-After` header over the limit. |
| **CSRF protection** | Scoped specifically to `documents/checkAiContent` in [app/Config/Filters.php](app/Config/Filters.php). Token is read from a `<meta name="X-CSRF-TOKEN">` tag (not the cookie directly — the CSRF cookie is `HttpOnly` and unreadable from JS) and refreshed from each JSON response, since the token rotates on every request. |
| **Auth required** | Endpoint sits behind the existing `logged_in` filter; unauthenticated requests are redirected before reaching the controller. |
| **Real file-type validation** | Checks the *sniffed* mime type (`UploadedFile::getMimeType()`), not the client-declared `Content-Type` or filename — confirmed it rejects a `.txt` file even when it lies about being a PDF. |
| **Size cap** | 10MB max before any parsing happens. |
| **Token/cost ceiling** | Capped at 12 chapters × 3,000 characters each sent to the model, regardless of actual document size — bounds the cost of a single check. |
| **Output escaping** | Chapter titles/notes from the model are rendered via `textContent`-based escaping in the frontend, not `innerHTML` directly — defends against a prompt-injection payload embedded in a malicious PDF trying to produce executable HTML. |
| **API key isolation** | `OPENROUTER_API_KEY` is read server-side only; the browser never sees it. The controller acts as a proxy. |

**Also fixed this session, unrelated to the AI check itself:**
- [app/Views/template/header.php](app/Views/template/header.php) was loading jQuery from `cdn-script.com` — not a real CDN, a supply-chain risk present on every page including login. Replaced with `cdnjs.cloudflare.com` (already trusted elsewhere in the same file) plus a Subresource Integrity hash computed directly from the file (cross-validated against two independent CDNs) and `crossorigin`/`referrerpolicy` attributes, so the browser refuses the script if it's ever tampered with.

**Known gaps — not covered by this work, by scope decision or otherwise:**
- **CSRF is still off everywhere else** in the app (login, register, document create/edit, search, account, users, logs export). Scoping it to just the AI-check endpoint was an explicit choice to avoid touching every existing form; extending it site-wide is a separate, larger task.
- **Two known CVEs in the CodeIgniter framework itself** (per `composer audit`): a file-extension validation bypass (`ext_in` rule, CVE-2026-48062) and an ImageMagick command-injection bug (CVE-2025-54418). Neither was introduced by this work; fixing them means upgrading the framework version.
- **No malware/content scanning** on the permanently-stored thesis PDF uploads (separate from the AI-check's temporary in-memory file handling).
- **The lockout has no automatic unlock timer** — once locked, the account stays inactive until an admin manually flips `status` back to `1` via `Users::edit()`. There's no "auto-unlock after N hours" mechanism; that was deliberately left manual to match the existing "contact the administrator" flow rather than introducing new state.

---

## 5. Setup Checklist

To activate these features after pulling this code:

1. Add the email and AI-check blocks above to your local `.env` (it's gitignored — never committed).
2. Replace the Gmail address + App Password with real values.
3. Replace `OPENROUTER_API_KEY` with a real key; consider whether the default free-ish model is acceptable or whether to switch to a paid one.
4. Submit a test thesis and confirm the email actually lands in an inbox (not yet verified for the submit/endorse/publish/revise events — see above).
5. Run a real AI content check and confirm a scored result renders correctly (not yet verified — see above).
6. Login lockout and the registration email are already confirmed working live — no further setup needed for those.
