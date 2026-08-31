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

// ── App links ────────────────────────────────────────────────────────────
// Google Play.
define('APP_CUSTOMER', 'https://play.google.com/store/apps/details?id=com.mechanic.mechanicconnect');
define('APP_MECHANIC', 'https://play.google.com/store/apps/details?id=com.mechanic.mechanics');
define('APP_GARAGE',   'https://play.google.com/store/apps/details?id=com.mechanic.garage');

// App Store. Both listings are published by OH-PEL AUTO LTD and were found in
// search; the listing pages themselves could not be opened from the build
// environment, so click each one once before relying on it.
//
// The country-neutral /app/id… form is deliberate: Apple sends each visitor to
// their own storefront, which a hard-coded /jm/ does not.
define('IOS_APP',         'https://apps.apple.com/app/id6754509101');  // customer app
define('IOS_APP_MECHANIC','https://apps.apple.com/app/id6754508075');  // staff app

// No App Store listing was found for the garage app (com.mechanic.garage).
// It appears to be Android-only. Add IOS_APP_GARAGE here if that changes.
define('IOS_APP_GARAGE', '');

// NOTE: nothing on the site renders these yet. Every "Download the app" link
// still points at Google Play, so an iPhone visitor is sent to a store they
// cannot install from. Wiring that up is a separate change.

define('SITE_URL', 'https://mechanic-connectja.mechanic-connect.net');

// Directory the site is served from: '' at the domain root, '/staging' when
// deployed into a subfolder. Detected rather than configured, so the same
// commit runs correctly at the root and in a staging folder with no edit.
// This is what makes staging possible without a subdomain.
$mc_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
define('BASE', ($mc_dir === '/' || $mc_dir === '.' || $mc_dir === '') ? '' : rtrim($mc_dir, '/'));

// True when the site is running from a subfolder, i.e. a staging copy.
define('IS_STAGING', BASE !== '');

// Where the CSV lead backup is written. Must sit ABOVE public_html.
// DOCUMENT_ROOT is empty under CLI and on some handlers, and dirname('') is
// '.', which would drop a file of customers' names and phone numbers inside
// the web root. Fall back to walking up from this file instead.
define('LEAD_LOG', !empty($_SERVER['DOCUMENT_ROOT'])
    ? dirname($_SERVER['DOCUMENT_ROOT']) . '/mc-leads.csv'
    : dirname(dirname(__DIR__)) . '/mc-leads.csv');

/**
 * Which app store to send this visitor to.
 *
 * Decided server-side from the user agent so it works with JavaScript off and
 * without a flash of the wrong link. An iPhone or iPad gets the App Store;
 * everything else gets Google Play. Where no iOS listing exists — the garage
 * app — it falls back to Play on its own, which is why the emptiness check
 * matters rather than just the platform.
 *
 * $which is 'customer', 'mechanic' or 'garage'.
 */
function on_ios() {
    return (bool) preg_match('/iPad|iPhone|iPod/i', $_SERVER['HTTP_USER_AGENT'] ?? '');
}

function store_has_ios($which = 'customer') {
    $ios = ['customer' => IOS_APP, 'mechanic' => IOS_APP_MECHANIC, 'garage' => IOS_APP_GARAGE];
    return on_ios() && !empty($ios[$which]);
}

function store_url($which = 'customer') {
    $ios  = ['customer' => IOS_APP,      'mechanic' => IOS_APP_MECHANIC, 'garage' => IOS_APP_GARAGE];
    $play = ['customer' => APP_CUSTOMER, 'mechanic' => APP_MECHANIC,     'garage' => APP_GARAGE];
    return store_has_ios($which) ? $ios[$which] : ($play[$which] ?? APP_CUSTOMER);
}

/** "App Store" or "Google Play", matching whatever store_url() just returned. */
function store_name($which = 'customer') {
    return store_has_ios($which) ? 'App Store' : 'Google Play';
}

/** The same thing as a call to action. Apple takes a definite article, Google
 *  does not, so the two cannot share one template string. */
function store_cta($which = 'customer') {
    return store_has_ios($which) ? 'Get it on the App Store' : 'Get it on Google Play';
}

function wa($text = '') {
    $u = 'https://wa.me/' . WA_NUMBER;
    return $text ? $u . '?text=' . rawurlencode($text) : $u;
}
function e($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
