<?php
// ─────────────────────────────────────────────────────────────
//  Lead handler — parts requests and provider applications.
//  Every lead is written to CSV first, then emailed. If mail
//  fails, the lead is still on disk. Nothing is ever lost.
// ─────────────────────────────────────────────────────────────
require_once __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ' . BASE . '/'); exit; }

// Honeypot — bots fill hidden fields, people don't.
if (!empty($_POST['company_website'])) { header('Location: ' . BASE . '/thank-you'); exit; }

$submitted = (string)($_POST['form_type'] ?? '');
$type = in_array($submitted, ['provider', 'guide'], true) ? $submitted : 'parts';

switch ($type) {
    case 'provider':
        $fields = ['applicant_type','name','business','phone','email','parish','years','specialisation','roadside','notes'];
        break;
    case 'guide':
        // Free guide download. Deliberately short — a lead magnet that asks for
        // eight fields does not get filled in.
        $fields = ['name','phone','parish','vehicle'];
        break;
    default:
        $fields = ['name','phone','vehicle','vin','part','location'];
}

$data = [];
foreach ($fields as $f) {
    $data[$f] = trim((string)($_POST[$f] ?? ''));
    if (mb_strlen($data[$f]) > 2000) $data[$f] = mb_substr($data[$f], 0, 2000);
}

// Required fields
switch ($type) {
    case 'provider': $required = ['applicant_type','name','phone','parish']; break;
    case 'guide':    $required = ['name','phone']; break;
    default:         $required = ['name','phone','vehicle','part'];
}

// Send an incomplete submission back to the form it came from. Dropping someone
// on the home page with no explanation loses the lead outright.
$formPage = ['parts' => '/parts', 'provider' => '/join', 'guide' => '/guide'][$type];
foreach ($required as $r) {
    if ($data[$r] === '') { header('Location: ' . BASE . $formPage . '?error=missing'); exit; }
}

$source = preg_replace('/[^a-zA-Z0-9_\-]/', '', (string)($_POST['source'] ?? 'direct')) ?: 'direct';
$stamp  = date('Y-m-d H:i:s');

// ── 1. write to CSV (above web root, not publicly readable) ──
$row = array_merge([$stamp, $type, $source], array_values($data));
// Neutralise spreadsheet formula injection
$row = array_map(function ($v) {
    return (isset($v[0]) && strpos("=+-@\t\r", $v[0]) !== false) ? "'" . $v : $v;
}, $row);

if ($fh = @fopen(LEAD_LOG, 'a')) {
    @flock($fh, LOCK_EX);
    @fputcsv($fh, $row);
    @flock($fh, LOCK_UN);
    @fclose($fh);
}

// ── 2. email it ──
// The subject is a mail header, so it must not carry a line break. trim()
// only touches the ends — a newline in the middle of a submitted name would
// let an attacker append their own headers (Bcc:, Reply-To:) to this mail.
// The body and the CSV keep their newlines; only the header is flattened.
$safeName = str_replace(["\r", "\n"], ' ', $data['name']);

switch ($type) {
    case 'provider': $subject = 'New provider application — ' . $safeName; break;
    case 'guide':    $subject = 'Guide download — ' . $safeName; break;
    default:         $subject = 'New parts request — ' . $safeName;
}

$lines = ["Received: $stamp", "Source: $source", ''];
foreach ($data as $k => $v) {
    if ($v !== '') $lines[] = ucfirst(str_replace('_', ' ', $k)) . ': ' . $v;
}
$lines[] = '';
$lines[] = 'Sent from ' . SITE_URL;

@mail(
    LEAD_EMAIL,
    $subject,
    implode("\n", $lines),
    "From: Mechanic Connect Website <noreply@" . parse_url(SITE_URL, PHP_URL_HOST) . ">\r\n" .
    "Reply-To: " . (filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: LEAD_EMAIL) . "\r\n" .
    "Content-Type: text/plain; charset=UTF-8"
);

header('Location: ' . BASE . '/thank-you?t=' . $type);
exit;
