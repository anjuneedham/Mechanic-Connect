<?php
$PAGE  = 'inspection';
$TITLE = 'J$1,500 15-Point Vehicle Inspection — Mechanic Connect JA';
$DESC  = 'Book a 15-point vehicle inspection with OH-PEL Auto for J$1,500 through the Mechanic Connect app. J$3,000 standard rate outside the app.';
require __DIR__ . '/includes/header.php';
?>


  <section class="hero gears">
    <div class="wrap">
      <p class="kicker">15-point vehicle inspection</p>
      <h1>Don't guess what's wrong with your vehicle.</h1>
      <p class="lede">A structured check by OH-PEL Auto that gives you a clearer picture of your vehicle's condition and flags areas that may need attention.</p>
    </div>
  </section>

  <section class="sec">
    <div class="wrap">
      <div class="pricebox">
        <p class="tag">Promotional price — booked in the app</p>
        <p class="price">J$1,500</p>
        <p class="sub">Booked through Mechanic Connect with OH-PEL Auto. Limited time offer.</p>
      </div>
      <table class="tbl">
        <tr><th>Booked in the app</th><td>J$1,500 — promotional rate, limited time</td></tr>
        <tr><th>Booked outside the app</th><td>J$3,000 — standard rate</td></tr>
        <tr><th>Carried out by</th><td>OH-PEL AUTO LIMITED</td></tr>
      </table>
      <div class="btns">
        <a class="btn btn--blue" href="<?= APP_CUSTOMER ?>">Book in the app</a>
        <a class="btn btn--line" href="<?= wa('I\'d like to book the J$1,500 15-point inspection') ?>">Ask about booking</a>
      </div>
    </div>
  </section>

  <section class="sec mist">
    <div class="wrap">
      <div class="plate-h"><h2>How to book it</h2></div>
      <div class="steps">
        <div class="step"><div><h3>Open Mechanic Connect</h3><p>Select the 15-Point Vehicle Inspection service, then OH-PEL Auto Services where required.</p></div></div>
        <div class="step"><div><h3>Pick your vehicle and date</h3><p>Select the vehicle, your preferred date, and an available time slot where one is offered.</p></div></div>
        <div class="step"><div><h3>Confirm and pay</h3><p>Review the J$1,500 service and complete the confirmation and payment steps shown in the app.</p></div></div>
      </div>
      <p style="margin-top:22px">No slots showing for the date you want? <a href="<?= wa('No inspection slots are showing for my date') ?>" style="font-weight:600;color:var(--blue)">Message us</a> and tell us the date you were trying to book.</p>
    </div>
  </section>

  <section class="sec">
    <div class="wrap">
      <div class="plate-h"><h2>What happens after</h2></div>
      <p class="lede" style="margin-top:13px">The inspection is the starting point, not the end of it.</p>
      <div class="steps">
        <div class="step"><div><h3>Findings and report</h3><p>OH-PEL carries out the inspection and gives you the findings.</p></div></div>
        <div class="step"><div><h3>Recommendations and quotation</h3><p>If work is needed, you get recommendations and a quotation — no obligation to proceed.</p></div></div>
        <div class="step"><div><h3>Book the work</h3><p>Approve what you want done and book it through Mechanic Connect.</p></div></div>
      </div>
      <!-- AMBER: the official list of the 15 inspection points is NOT yet approved by OH-PEL
           management (Knowledge Base §6 and §22). Do not list individual points until the
           approved list is supplied in writing. Do not invent them. -->
      <div class="note">
        <p><strong>Build note — remove before launch</strong>The itemised list of all 15 inspection points goes here, once OH-PEL management has approved it in writing. Do not populate this from guesswork.</p>
      </div>
    </div>
  </section>
</div>

<!-- ════════════ PARTS ════════════ -->

<?php require __DIR__ . '/includes/footer.php'; ?>
