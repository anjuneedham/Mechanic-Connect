<?php
$PAGE  = '';
$TITLE = 'Thank you — Mechanic Connect JA';
$DESC  = 'We have your details.';
require __DIR__ . '/includes/header.php';
$t = in_array($_GET['t'] ?? '', ['provider', 'guide'], true) ? $_GET['t'] : 'parts';
?>

<section class="hero gears">
  <div class="wrap">
    <p class="kicker"><?= $t === 'guide' ? 'Your guide is ready' : 'Received' ?></p>
    <h1><?= $t === 'guide' ? 'Here is your guide.' : 'We have your details.' ?></h1>
    <p class="lede"><?php
      if ($t === 'provider') {
          echo 'Thanks for applying. The team will review your information and contact you about onboarding and verification.';
      } elseif ($t === 'guide') {
          echo 'The Roadside Job Card — six pages on what to check, what to say and what to ask before anyone touches your vehicle. Download it below and keep it on your phone.';
      } else {
          echo 'Thanks. We will come back to you to confirm the part and the price. This does not place an order.';
      }
    ?></p>
    <div class="btns">
      <?php if ($t === 'guide'): ?>
        <a class="btn btn--white" href="<?= BASE ?>/assets/The-Roadside-Job-Card.pdf" download>Download the guide (PDF)</a>
      <?php endif; ?>
      <a class="btn btn--line" href="<?= wa($t === 'provider'
        ? 'Hi, I just sent an application to join Mechanic Connect.'
        : ($t === 'guide'
          ? 'Hi, I just downloaded The Roadside Job Card.'
          : 'Hi, I just sent a parts request through the website.')) ?>">Continue on WhatsApp</a>
      <a class="btn btn--line" href="<?= BASE ?>/">Back to home</a>
    </div>
  </div>
</section>

<section class="sec">
  <div class="wrap">
    <div class="plate-h"><h2>While you wait</h2></div>
    <div class="paths">
      <a class="pth pth--hot" href="<?= BASE ?>/roadside"><h3>Get roadside help</h3><p>Dead battery, flat tyre or a breakdown right now.</p><span class="act">Start here</span></a>
      <a class="pth" href="<?= BASE ?>/inspection"><h3>Book an inspection</h3><p>15-point vehicle inspection with OH-PEL Auto. J$1,500 in the app.</p><span class="act">See the offer</span></a>
      <a class="pth" href="<?= store_url('customer') ?>"><h3>Download the app</h3><p>Request service, compare quotes and pay securely.</p><span class="act"><?= store_cta('customer') ?></span></a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
