<?php
$PAGE  = 'services';
$TITLE = 'Automotive Services — Mechanic Connect JA';
$DESC  = 'Servicing, diagnostics, brakes, suspension, tyres and repairs from verified garages across the Kingston Metropolitan Area.';
require __DIR__ . '/includes/header.php';
?>


  <section class="hero gears">
    <div class="wrap">
      <p class="kicker">Automotive services</p>
      <h1>Stress-free repair, right at your fingertips.</h1>
      <p class="lede">No more calling around, no more guessing prices, no more wondering who to trust. Request service, compare quotes from verified providers, choose who works on your vehicle, and pay securely.</p>
      <div class="btns">
        <a class="btn btn--white" href="<?= wa() ?>">Ask a question on WhatsApp</a>
        <a class="btn btn--line" href="<?= store_url('customer') ?>">Download the app</a>
      </div>
    </div>
  </section>

  <section class="sec">
    <div class="wrap">
      <div class="plate-h"><h2>What's available</h2></div>
      <p class="lede" style="margin-top:13px">Through OH-PEL Auto and participating garages on the network.</p>
      <ul class="checks">
        <li>15-point vehicle inspection</li>
        <li>General servicing</li>
        <li>Vehicle repairs</li>
        <li>Diagnostics</li>
        <li>Brake services</li>
        <li>Suspension and frontend services</li>
        <li>Tyre services</li>
        <li>Battery-related services</li>
        <li>Parts</li>
        <li>Preventative maintenance</li>
        <li>Selected fleet services</li>
      </ul>
    </div>
  </section>

  <section class="sec mist">
    <div class="wrap">
      <div class="plate-h"><h2>Not sure what's wrong?</h2></div>
      <p class="lede" style="margin-top:13px">You don't need to diagnose the vehicle yourself. Tell us what you're experiencing — a sound, a warning light, a vibration, a starting problem, or a change in how it drives. Depending on the symptoms, an inspection or a diagnostic assessment may be the right next step.</p>
      <div class="note">
        <p><strong>On safety</strong>If an issue could affect how safely the vehicle drives, we won't tell you it's fine based on a chat. The vehicle should be properly inspected before deciding whether it's safe to keep driving. If you're stranded now, start with roadside assistance.</p>
      </div>
      <div class="btns">
        <a class="btn btn--blue" href="<?= BASE ?>/inspection">Book an inspection</a>
        <a class="btn btn--red" href="<?= BASE ?>/roadside">I'm stranded now</a>
      </div>
    </div>
  </section>

  <section class="sec">
    <div class="wrap">
      <div class="plate-h"><h2>What drivers say</h2></div>
      <div class="empty"><p>Reviews will appear here once the first jobs are complete.</p></div>
      <!-- DO NOT populate with invented reviews. Real, attributable reviews only, with client sign-off. -->
    </div>
  </section>

  <section class="sec blue gears">
    <div class="wrap">
      <div class="plate-h"><h2>Get the app</h2></div>
      <p class="lede" style="margin-top:14px">Free to download. Free to use. You only pay for the work you ask for.</p>
      <div class="btns">
        <a class="btn btn--white" href="<?= IOS_APP ?>"><img class="btn__ico" src="<?= BASE ?>/assets/img/app-customer.jpg" alt="" width="256" height="256" loading="lazy">App&nbsp;Store</a>
        <a class="btn btn--white" href="<?= APP_CUSTOMER ?>"><img class="btn__ico" src="<?= BASE ?>/assets/img/app-customer.jpg" alt="" width="256" height="256" loading="lazy">Google&nbsp;Play</a>
      </div>
      <!-- Both listings are live and recorded in includes/config.php: the customer
           app at id6754509101 and the staff app at id6754508075, both published by
           OH-PEL AUTO LTD. Elsewhere on the site store_url() picks the store from the
           visitor's device; here there is room to name both outright. There is still
           no App Store listing for the garage app. -->
    </div>
  </section>
</div>

<!-- ════════════ MECHANICS ════════════ -->

<?php require __DIR__ . '/includes/footer.php'; ?>
