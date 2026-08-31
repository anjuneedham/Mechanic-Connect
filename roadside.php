<?php
$PAGE  = 'roadside';
$TITLE = 'Roadside Assistance — Mechanic Connect JA';
$DESC  = 'Stranded in Kingston? Battery, flat tyre, breakdown or fuel. Request roadside help from verified mechanics through Mechanic Connect.';
require __DIR__ . '/includes/header.php';
?>


  <section class="hero gears">
    <div class="wrap">
      <p class="kicker kicker--red">Roadside assistance</p>
      <h1>Stranded? Mechanic Connect it.</h1>
      <p class="lede">Car won't start, tyre gone flat, or the vehicle stopped on you. Send the details and we'll guide you through requesting help.</p>
      <div class="btns">
        <a class="btn btn--red" href="<?= wa('I need roadside assistance') ?>">Request help on WhatsApp</a>
        <a class="btn btn--white" href="<?= store_url('customer') ?>">Request in the app</a>
      </div>
    </div>
  </section>

  <section class="sec">
    <div class="wrap">
      <div class="safety">
        <div class="safety__top">Before anything else — are you safe?</div>
        <div class="safety__body">
          <p>If there is immediate danger, an accident with injuries, fire, smoke, a medical emergency or another life-threatening situation, contact the appropriate emergency service first.</p>
          <p>If you are safe and away from moving traffic, carry on below and we'll collect what's needed to get you help.</p>
        </div>
      </div>

      <div class="plate-h" style="margin-top:46px"><h2>What we help with</h2></div>
      <ul class="checks">
        <li>Battery and jump-start assistance</li>
        <li>Flat tyre assistance</li>
        <li>Minor roadside mechanical problems</li>
        <li>Emergency fuel assistance</li>
        <li>Vehicle breakdown assistance</li>
        <li>Towing and recovery coordination, where available</li>
        <li>Connection to an available mechanic or roadside agent</li>
      </ul>
    </div>
  </section>

  <section class="sec mist">
    <div class="wrap">
      <div class="plate-h"><h2>What to have ready</h2></div>
      <p class="lede" style="margin-top:13px">The faster we have these, the faster your request can be handled.</p>
      <div class="feats">
        <div class="feat"><h3>Where you are</h3><p>A landmark, an address, or your shared location.</p></div>
        <div class="feat"><h3>The vehicle</h3><p>Year, make and model. Registration number if you have it.</p></div>
        <div class="feat"><h3>What happened</h3><p>What the vehicle is doing, and whether it can still move.</p></div>
        <div class="feat"><h3>How to reach you</h3><p>Your name and a WhatsApp or phone number.</p></div>
      </div>
    </div>
  </section>

  <section class="sec blue gears">
    <div class="wrap">
      <div class="plate-h"><h2>How the request works</h2></div>
      <div class="steps">
        <div class="step"><div><h3>You send the details</h3><p>By WhatsApp or in the app. We confirm you're in a safe spot and collect what's needed.</p></div></div>
        <div class="step"><div><h3>The request goes out</h3><p>Your request is put to available mechanics and roadside agents in the area.</p></div></div>
        <div class="step"><div><h3>A provider is confirmed</h3><p>You are told once a provider has accepted. We won't tell you help is coming until it actually is.</p></div></div>
      </div>
      <div class="note">
        <p><strong>Straight with you</strong>We don't give arrival times or promise a provider before one has accepted. If we can't reach anyone, we'll tell you that instead of leaving you waiting.</p>
      </div>
      <div class="btns">
        <a class="btn btn--red" href="<?= wa('I need roadside assistance') ?>">Request help on WhatsApp</a>
      </div>
      <!-- AMBER: roadside operating hours and roadside-specific coverage area are NOT approved.
           Knowledge Base §22 lists both as outstanding. Do not add hours, a "24/7" claim,
           or a parish coverage list here without written director sign-off. -->
    </div>
  </section>
</div>

<!-- ════════════ INSPECTION ════════════ -->

<?php require __DIR__ . '/includes/footer.php'; ?>
