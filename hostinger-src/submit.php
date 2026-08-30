<?php
/**
 * Lead capture for Mechanic Connect JA on Hostinger.
 *
 * Netlify records form submissions for you. Shared hosting does not, so this
 * script is the replacement: it validates the submission, writes it to a CSV
 * that cannot be reached from the web, emails it, and sends the visitor on to
 * the thank-you page.
 *
 * The CSV is the important part. Email from shared hosting gets classified as
 * spam often enough that it cannot be the only copy — the file is the record,
 * the email is the notification.
 *
 * ── CONFIGURE ──────────────────────────────────────────────────────────────
 * Set NOTIFY_TO below. Everything else works as shipped.
 */

// ---------------------------------------------------------------- settings --
const NOTIFY_TO   = 'mechanicconnectja@gmail.com';
const SITE_NAME   = 'Mechanic Connect JA';
const THANKS_PAGE = 'thanks.html';
const LEAD_DIR    = __DIR__ . '/_leads';
const MAX_FIELD   = 500;   // characters; anything longer is a bot or a mistake
const MIN_SECONDS = 2;     // a human takes longer than this to fill four fields

// ------------------------------------------------------------------ helpers --

/** Trim, cap the length, and strip anything that could forge a mail header. */
function clean(string $key): string
{
    $value = isset($_POST[$key]) ? (string) $_POST[$key] : '';
    $value = trim($value);
    $value = str_replace(["\r", "\n", "%0a", "%0d"], ' ', $value);
    if (function_exists('mb_substr')) {
        return mb_substr($value, 0, MAX_FIELD);
    }
    return substr($value, 0, MAX_FIELD);
}

function finish(string $status): void
{
    // A fetch() follows the redirect and reports ok; a plain browser submit
    // lands on the page. One code path serves both.
    header('Location: ' . THANKS_PAGE . '?s=' . $status, true, 303);
    exit;
}

// ------------------------------------------------------------------- guards --

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html', true, 303);
    exit;
}

// Honeypot: hidden from people, filled by the bots that complete every field.
if (clean('bot-field') !== '') {
    finish('ok');   // say nothing useful to the bot
}

// Submitted implausibly fast — also a bot.
$started = isset($_POST['started']) ? (int) $_POST['started'] : 0;
if ($started > 0 && (time() - $started) < MIN_SECONDS) {
    finish('ok');
}

$name   = clean('name');
$phone  = clean('phone');
$parish = clean('parish');
$need   = clean('need');

if ($name === '' || $phone === '' || $parish === '' || $need === '') {
    finish('incomplete');
}

// A Jamaican number is 10 digits; allow 7 to 15 so international and local
// formats both pass, and let the office judge the rest.
$digits = preg_replace('/\D+/', '', $phone);
if (strlen($digits) < 7 || strlen($digits) > 15) {
    finish('badphone');
}

// ------------------------------------------------------- record, then notify --

$row = [
    date('Y-m-d H:i:s'),
    clean('form-name') !== '' ? clean('form-name') : 'unknown',
    $name,
    $phone,
    $parish,
    $need,
    clean('vehicle'),
    clean('utm_source'),
    clean('utm_medium'),
    clean('utm_campaign'),
    clean('landing_page'),
    isset($_SERVER['HTTP_REFERER']) ? substr((string) $_SERVER['HTTP_REFERER'], 0, 300) : '',
];

if (!is_dir(LEAD_DIR)) {
    @mkdir(LEAD_DIR, 0750, true);
}

$csv = LEAD_DIR . '/leads.csv';
$new = !file_exists($csv);
$fh  = @fopen($csv, 'a');

if ($fh !== false) {
    if (flock($fh, LOCK_EX)) {
        if ($new) {
            fputcsv($fh, ['received', 'form', 'name', 'phone', 'parish', 'need',
                          'vehicle', 'utm_source', 'utm_medium', 'utm_campaign',
                          'landing_page', 'referrer']);
        }
        fputcsv($fh, $row);
        fflush($fh);
        flock($fh, LOCK_UN);
    }
    fclose($fh);
}

// The email is a notification, not the record. If it fails, the CSV still has
// the lead, so a failure here must never look like a failure to the visitor.
$lines = [
    'New enquiry from the ' . SITE_NAME . ' website.',
    '',
    'Name:    ' . $name,
    'Phone:   ' . $phone,
    'Parish:  ' . $parish,
    'Needs:   ' . $need,
];
if (clean('vehicle') !== '') {
    $lines[] = 'Vehicle: ' . clean('vehicle');
}
$lines[] = '';
$lines[] = 'WhatsApp them: https://wa.me/' . $digits;
if (clean('utm_source') !== '' || clean('utm_campaign') !== '') {
    $lines[] = '';
    $lines[] = 'Came from: ' . clean('utm_source') . ' / ' . clean('utm_medium')
             . ' / ' . clean('utm_campaign');
}
$lines[] = '';
$lines[] = 'Form: ' . $row[1] . '  ·  ' . $row[0];

$host = isset($_SERVER['HTTP_HOST']) ? preg_replace('/[^a-z0-9.\-]/i', '', (string) $_SERVER['HTTP_HOST']) : 'localhost';
$headers = "From: " . SITE_NAME . " <noreply@" . $host . ">\r\n"
         . "Content-Type: text/plain; charset=UTF-8\r\n";

@mail(NOTIFY_TO, 'Website enquiry — ' . $name . ' (' . $parish . ')',
      implode("\n", $lines), $headers);

finish('ok');
