<?php
require_once __DIR__ . '/config.php';
$PAGE  = $PAGE  ?? 'home';
$TITLE = $TITLE ?? 'Mechanic Connect JA — The smartest way to get your auto services';
$DESC  = $DESC  ?? 'Roadside assistance, J$1,500 15-point vehicle inspection and parts requests across the Kingston Metropolitan Area. Verified mechanics and garages. No platform fees for customers.';
$nav = [
  'roadside'   => ['Roadside',   '/roadside'],
  'inspection' => ['Inspection', '/inspection'],
  'parts'      => ['Parts',      '/parts'],
  'services'   => ['Services',   '/services'],
  'guide'      => ['Free Guide', '/guide'],
  'mechanics'  => ['Join Us',    '/join'],
  'about'      => ['About',      '/about'],
  'faqs'       => ['FAQs',       '/faqs'],
];
?><!DOCTYPE html>
<html lang="en-JM">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($TITLE) ?></title>
<meta name="description" content="<?= e($DESC) ?>">
<?php if (IS_STAGING): ?>
<!-- Running from a subfolder, so this is a staging copy. Keep it out of
     search results entirely — a second indexed copy of the site would
     compete with the real one. -->
<meta name="robots" content="noindex, nofollow">
<?php else: ?>
<link rel="canonical" href="<?= SITE_URL ?><?= e($_SERVER['REQUEST_URI'] ?? '/') ?>">
<?php endif; ?>
<meta property="og:type" content="website">
<meta property="og:site_name" content="Mechanic Connect JA">
<meta property="og:title" content="<?= e($TITLE) ?>">
<meta property="og:description" content="<?= e($DESC) ?>">
<meta property="og:url" content="<?= SITE_URL ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="theme-color" content="#0B5FD4">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= BASE ?>/assets/css/style.css">
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"AutoRepair","name":"Mechanic Connect JA",
"description":"Platform connecting Jamaican vehicle owners with verified garages and independent mechanics.",
"url":"<?= SITE_URL ?>","telephone":"+1<?= str_replace('-','',SUPPORT_PHONE) ?>",
"email":"<?= LEAD_EMAIL ?>",
"address":{"@type":"PostalAddress","streetAddress":"26 Eastwood Park Road","addressLocality":"Kingston 10","addressCountry":"JM"},
"areaServed":["Kingston","St. Andrew","Portmore","Spanish Town"]}
</script>
</head>
<body>
<a class="skip" href="#main">Skip to content</a>

<header class="hdr">
  <div class="hdr__in">
    <a class="logo" href="<?= BASE ?>/">
      <span>
        <span class="logo__plate">Mechanic Connect</span>
        <span class="logo__sub">Jamaica</span>
      </span>
    </a>
    <button class="navtog" id="navtog" aria-expanded="false" aria-controls="nav">Menu</button>
  </div>
  <nav class="nav" id="nav" aria-label="Main">
    <?php foreach ($nav as $k => $v): ?>
      <a href="<?= BASE . $v[1] ?>"<?= $PAGE === $k ? ' aria-current="page"' : '' ?>><?= $v[0] ?></a>
    <?php endforeach; ?>
  </nav>
</header>

<main id="main">
