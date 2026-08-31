<?php
$PAGE  = '';
$TITLE = 'Thank you — Mechanic Connect JA';
$DESC  = 'We have your details.';
require __DIR__ . '/includes/header.php';
$t = ($_GET['t'] ?? '') === 'provider' ? 'provider' : 'parts';
?>

<section class="hero gears">
  <div class="wrap">
    <p class="kicker">Received</p>
    <h1>We have your details.</h1>
    <p class="lede"><?= $t === 'provider'
      ? 'Thanks for applying. The team will review your information and contact you about onboarding and verification.'
      : 'Thanks. We will come back to you to confirm the part and the price. This does not place an order.' ?></p>
    <div class="btns">
      <a class="btn btn--white" href="<?= wa($t === 'provider'
        ? 'Hi, I just sent an application to join Mechanic Connect.'
        : 'Hi, I just sent a parts request through the website.') ?>">Continue on WhatsApp</a>
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
      <a class="pth" href="<?= APP_CUSTOMER ?>"><h3>Download the app</h3><p>Request service, compare quotes and pay securely.</p><span class="act">Get it on Google Play</span></a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
