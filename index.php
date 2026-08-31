<?php
$PAGE  = 'home';
$TITLE = 'Mechanic Connect JA — The smartest way to get your auto services';
$DESC  = 'Roadside assistance, J$1,500 15-point vehicle inspection and parts requests across the Kingston Metropolitan Area. Verified mechanics and garages.';
require __DIR__ . '/includes/header.php';
?>


  <section class="hero gears">
    <div class="wrap">
      <p class="kicker">Car trouble? Mechanic Connect it.</p>
      <h1>Stranded? We'll get you moving.</h1>
      <p class="lede">The smartest way to get your auto services. Roadside help, vehicle inspections and parts — from verified mechanics and garages across the Kingston Metropolitan Area.</p>
      <div class="btns">
        <a class="btn btn--red" href="<?= BASE ?>/roadside">Get roadside help</a>
        <a class="btn btn--white" href="<?= wa() ?>">Message us on WhatsApp</a>
      </div>
      <p style="margin-top:15px;font-size:15px;color:rgba(255,255,255,.75)">No account needed to ask a question. Customers pay no platform or booking fees.</p>
    </div>
  </section>

  <section class="sec">
    <div class="wrap">
      <div class="plate-h"><h2>What do you need?</h2></div>
      <div class="paths">
        <a class="pth pth--hot" href="<?= BASE ?>/roadside">
          <h3>Get roadside help</h3>
          <p>Dead battery, flat tyre, breakdown, or the car just won't start.</p>
          <span class="act">Start here</span>
        </a>
        <a class="pth" href="<?= BASE ?>/inspection">
          <h3>Book an inspection</h3>
          <p>15-point vehicle inspection with OH-PEL Auto. J$1,500 booked in the app.</p>
          <span class="act">See the offer</span>
        </a>
        <a class="pth" href="<?= BASE ?>/parts">
          <h3>Request a part</h3>
          <p>Send your vehicle details and the part you need. We help find it.</p>
          <span class="act">Send a request</span>
        </a>
        <a class="pth" href="<?= BASE ?>/services">
          <h3>Find automotive services</h3>
          <p>Servicing, diagnostics, brakes, suspension, tyres and major repairs.</p>
          <span class="act">Browse services</span>
        </a>
        <a class="pth" href="<?= BASE ?>/join">
          <h3>Join as a mechanic or garage</h3>
          <p>Take jobs, quote, get paid. Apprenticeship enquiries welcome too.</p>
          <span class="act">Apply to join</span>
        </a>
        <a class="pth" href="<?= store_url('customer') ?>">
          <h3>Download the Customer App</h3>
          <p>Request service, compare quotes and pay securely from your phone.</p>
          <span class="act"><?= store_cta('customer') ?></span>
        </a>
      </div>
    </div>
  </section>

  <section class="sec mist">
    <div class="wrap">
      <div class="plate-h"><h2>How it works</h2></div>
      <div class="steps">
        <div class="step"><div><h3>Tell us what's wrong</h3><p>Message us on WhatsApp or open the app. Describe the problem, add a photo if it helps, and set your location.</p></div></div>
        <div class="step"><div><h3>Compare quotes, or book direct</h3><p>Receive quotes from available garages and mechanics, or book straight with a provider offering what you need.</p></div></div>
        <div class="step"><div><h3>Pay when it's finished</h3><p>Payment is made inside the app and held. It reaches the mechanic only after you confirm the job is complete.</p></div></div>
      </div>
    </div>
  </section>

  <section class="sec">
    <div class="wrap">
      <div class="plate-h"><h2>Why drivers use it</h2></div>
      <div class="feats">
        <div class="feat"><h3>Verified providers</h3><p>Every garage and mechanic goes through a verification process before taking work.</p></div>
        <div class="feat"><h3>No customer fees</h3><p>No platform fee, no booking fee. You pay for the repair, nothing on top.</p></div>
        <div class="feat"><h3>Quotes before work starts</h3><p>See the price first and pick what suits your budget.</p></div>
        <div class="feat"><h3>Payment held until you confirm</h3><p>Funds sit securely in the app and release only on your say-so.</p></div>
        <div class="feat"><h3>Parts through OH-PEL Auto</h3><p>When parts are needed, sourcing runs through OH-PEL AUTO LIMITED, the official parts partner.</p></div>
        <div class="feat"><h3>Built for Jamaican roads</h3><p>Made here, for the conditions and the traffic drivers actually deal with.</p></div>
      </div>
    </div>
  </section>

  <section class="sec blue gears">
    <div class="wrap">
      <div class="plate-h"><h2>Where we're live</h2></div>
      <p class="lede" style="margin-top:14px">Launching in the Kingston Metropolitan Area, with other parishes to follow.</p>
      <ul class="area"><li>Kingston</li><li>St. Andrew</li><li>Portmore</li><li>Spanish Town</li></ul>
      <div class="btns">
        <a class="btn btn--red" href="<?= BASE ?>/roadside">Get roadside help</a>
        <a class="btn btn--white" href="<?= wa() ?>">Ask a question</a>
      </div>
    </div>
  </section>
</div>

<!-- ════════════ ROADSIDE ════════════ -->

<?php require __DIR__ . '/includes/footer.php'; ?>
