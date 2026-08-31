<?php
$PAGE  = 'parts';
$TITLE = 'Request a Car Part — Mechanic Connect JA';
$DESC  = 'Send your vehicle year, make, model and VIN and we help source the correct part through OH-PEL AUTO LIMITED.';
require __DIR__ . '/includes/header.php';
?>


  <section class="hero gears">
    <div class="wrap">
      <p class="kicker">Parts request</p>
      <h1>Need a car part? Let us help you find it.</h1>
      <p class="lede">Send the vehicle details and what you're looking for. Parts sourcing runs through OH-PEL AUTO LIMITED, our official parts partner.</p>
      <div class="btns">
        <a class="btn btn--white" href="#partsform">Send a parts request</a>
        <a class="btn btn--line" href="<?= wa('I\'m looking for a part') ?>">Ask on WhatsApp</a>
      </div>
    </div>
  </section>

  <section class="sec">
    <div class="wrap">
      <div class="plate-h"><h2>What we need from you</h2></div>
      <p class="lede" style="margin-top:13px">The chassis or VIN matters most. It's what stops the wrong part turning up.</p>
      <ul class="checks">
        <li>Vehicle year, make and model</li>
        <li>Chassis or VIN number</li>
        <li>The part name or description, and how many</li>
        <li>A part number or brand preference, if you have one</li>
        <li>New or used preference</li>
        <li>A photo of the old part or the vehicle plate</li>
        <li>Your location and how urgent it is</li>
      </ul>
      <div class="note">
        <p><strong>On price and delivery</strong>Price is confirmed once we know the exact vehicle and part. Delivery depends on the part, the supplier and where you are — the option and any charge are given once those are confirmed. We don't quote a part before we can verify it fits.</p>
      </div>
    </div>
  </section>

  <section class="sec mist" id="partsform">
    <div class="wrap">
      <div class="plate-h"><h2>Send a parts request</h2></div>
      <form class="form" name="parts-request" method="POST" action="/submit.php">
        <div class="fld"><label for="pt-name">Your name <span class="req">*</span></label><input id="pt-name" name="name" required></div>
        <div class="fld"><label for="pt-phone">WhatsApp or phone <span class="req">*</span></label><input id="pt-phone" name="phone" type="tel" required></div>
        <div class="fld"><label for="pt-veh">Vehicle year, make and model <span class="req">*</span></label><input id="pt-veh" name="vehicle" placeholder="e.g. 2012 Toyota Axio" required></div>
        <div class="fld"><label for="pt-vin">Chassis / VIN</label><input id="pt-vin" name="vin"><p class="hint">Helps us confirm the part actually fits your vehicle.</p></div>
        <div class="fld"><label for="pt-part">Part needed <span class="req">*</span></label><textarea id="pt-part" name="part" required></textarea></div>
        <div class="fld"><label for="pt-loc">Your location</label><input id="pt-loc" name="location"></div>
        <div style="position:absolute;left:-9999px" aria-hidden="true">
          <label for="company_website">Leave this empty</label>
          <input id="company_website" name="company_website" tabindex="-1" autocomplete="off">
        </div>
        <input type="hidden" name="form_type" value="parts">
        <input type="hidden" name="source" class="js-source" value="direct">
        <button class="btn btn--blue" type="submit" style="width:100%">Send my parts request</button>
        <p class="hint">We'll come back to you to confirm the part and price. Sending this does not place an order.</p>
      </form>
    </div>
  </section>
</div>

<!-- ════════════ SERVICES ════════════ -->

<?php require __DIR__ . '/includes/footer.php'; ?>
