<?php
// ─────────────────────────────────────────────────────────────
//  Mechanic Connect JA — site configuration
//  Change values here only. Nothing else in the site hard-codes them.
// ─────────────────────────────────────────────────────────────

// Where form submissions are emailed.
define('LEAD_EMAIL', 'mechanicconnectja@gmail.com');

// Approved WhatsApp number, digits only, country code first.
define('WA_NUMBER', '18764703144');

// Customer support line and hours (NOT roadside hours).
define('SUPPORT_PHONE', '876-343-4189');
define('SUPPORT_HOURS', 'Mon–Fri, 9:30am–6:00pm');
define('SITE_ADDRESS', '26 Eastwood Park Road, Kingston 10, Jamaica');

// App links. Add IOS_APP only when a live App Store URL is confirmed.
define('APP_CUSTOMER', 'https://play.google.com/store/apps/details?id=com.mechanic.mechanicconnect');
define('APP_MECHANIC', 'https://play.google.com/store/apps/details?id=com.mechanic.mechanics');
define('APP_GARAGE',   'https://play.google.com/store/apps/details?id=com.mechanic.garage');
define('IOS_APP', '');   // leave empty until confirmed

define('SITE_URL', 'https://mechanic-connectja.mechanic-connect.net');

// Where the CSV lead backup is written. Must sit ABOVE public_html.
// DOCUMENT_ROOT is empty under CLI and on some handlers, and dirname('') is
// '.', which would drop a file of customers' names and phone numbers inside
// the web root. Fall back to walking up from this file instead.
define('LEAD_LOG', !empty($_SERVER['DOCUMENT_ROOT'])
    ? dirname($_SERVER['DOCUMENT_ROOT']) . '/mc-leads.csv'
    : dirname(dirname(__DIR__)) . '/mc-leads.csv');

function wa($text = '') {
    $u = 'https://wa.me/' . WA_NUMBER;
    return $text ? $u . '?text=' . rawurlencode($text) : $u;
}
function e($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
