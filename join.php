<?php
$PAGE  = 'mechanics';
$TITLE = 'Join as a Mechanic or Garage — Mechanic Connect JA';
$DESC  = 'Turn your skills. Help drivers. Earn more. 15% capped commission, minimum J$500 and maximum J$25,000 per job.';
require __DIR__ . '/includes/header.php';
?>


  <section class="hero gears">
    <div class="wrap">
      <p class="kicker">Mechanics, garages &amp; roadside agents</p>
      <h1>Turn your skills. Help drivers. Earn more.</h1>
      <p class="lede">Instead of relying on walk-ins and word of mouth, get connected to vehicle owners actively looking for service in your area. Receive job requests, send quotes, manage work, and get paid.</p>
      <div class="btns">
        <a class="btn btn--white" href="#signup">Start your application</a>
      </div>
    </div>
  </section>

  <section class="sec">
    <div class="wrap">
      <div class="plate-h"><h2>Who we're looking for</h2></div>
      <ul class="checks">
        <li>Experienced mechanics</li>
        <li>Mobile mechanics</li>
        <li>Independent mechanics</li>
        <li>Garage owners</li>
        <li>Roadside assistance providers</li>
        <li>Tow and recovery operators</li>
        <li>People interested in learning — apprenticeship enquiries welcome</li>
      </ul>
      <p style="margin-top:20px">We're especially interested in mechanics who can assist with repairs, diagnostics, mobile services and roadside assistance. All providers complete registration and verification before being activated.</p>
    </div>
  </section>

  <section class="sec blue gears">
    <div class="wrap">
      <div class="plate-h"><h2>Fair, capped commission</h2></div>
      <p class="lede" style="margin-top:14px">The effective rate falls as job value rises, so bigger jobs pay a lower percentage overall.</p>
      <table class="tbl">
        <tr><th>Commission</th><td>15% on completed jobs</td></tr>
        <tr><th>Minimum</th><td>J$500 per job</td></tr>
        <tr><th>Maximum cap</th><td>J$25,000 per job</td></tr>
        <tr><th>Early Partner rate</th><td>Discounted for the first 50 garages, for their first 60 days</td></tr>
        <tr><th>Standard payouts</th><td>Twice weekly — Wednesday and Friday</td></tr>
        <tr><th>Faster payout</th><td>Next business day, for a processing fee</td></tr>
      </table>
      <p style="margin-top:18px">Subject to the applicable platform terms. After 60 days, Early Partner accounts move automatically to the standard commission structure.</p>
    </div>
  </section>

  <section class="sec mist">
    <div class="wrap">
      <div class="plate-h"><h2>Why join</h2></div>
      <div class="feats">
        <div class="feat"><h3>More customers</h3><p>Access a growing network of vehicle owners actively seeking repairs and servicing.</p></div>
        <div class="feat"><h3>A digital presence</h3><p>Build a professional profile showing your services, specialties, certifications and reviews.</p></div>
        <div class="feat"><h3>Manage work in one app</h3><p>Accept or decline jobs, send quotes, track status and talk to customers directly.</p></div>
        <div class="feat"><h3>Get paid securely</h3><p>Once the customer confirms the job, funds release to your Mechanic Connect wallet.</p></div>
      </div>
      <div class="btns">
        <a class="btn btn--blue" href="<?= APP_MECHANIC ?>">Mechanic App</a>
        <a class="btn btn--blue" href="<?= APP_GARAGE ?>">Garage App</a>
      </div>
    </div>
  </section>

  <section class="sec" id="signup">
    <div class="wrap">
      <div class="plate-h"><h2>Apply to join</h2></div>
      <p class="lede" style="margin-top:13px">Fill this in and the team will contact you to guide you through onboarding and verification.</p>
      <form class="form" name="provider-signup" method="POST" action="/submit.php">
        <div class="fld"><label for="f-type">I'm applying as <span class="req">*</span></label>
          <select id="f-type" name="applicant_type" required>
            <option value="">Select one</option>
            <option>Independent mechanic</option>
            <option>Mobile mechanic</option>
            <option>Garage owner</option>
            <option>Roadside assistance provider</option>
            <option>Tow / recovery operator</option>
            <option>Apprenticeship / willing to learn</option>
          </select></div>
        <div class="fld"><label for="f-name">Full name <span class="req">*</span></label><input id="f-name" name="name" required></div>
        <div class="fld"><label for="f-biz">Business or garage name</label><input id="f-biz" name="business"></div>
        <div class="fld"><label for="f-num">WhatsApp or phone <span class="req">*</span></label><input id="f-num" name="phone" type="tel" required></div>
        <div class="fld"><label for="f-email">Email</label><input id="f-email" name="email" type="email"></div>
        <div class="fld"><label for="f-parish">Parish or working area <span class="req">*</span></label>
          <select id="f-parish" name="parish" required>
            <option value="">Select a parish</option>
            <option>Kingston</option><option>St. Andrew</option><option>St. Catherine</option>
            <option>Clarendon</option><option>Manchester</option><option>St. Elizabeth</option>
            <option>Westmoreland</option><option>Hanover</option><option>St. James</option>
            <option>Trelawny</option><option>St. Ann</option><option>St. Mary</option>
            <option>Portland</option><option>St. Thomas</option>
          </select></div>
        <div class="fld"><label for="f-years">Years of experience</label><input id="f-years" name="years"></div>
        <div class="fld"><label for="f-spec">Main area of expertise</label>
          <select id="f-spec" name="specialisation">
            <option value="">Select one</option>
            <option>Engine</option><option>Transmission</option><option>Electrical</option>
            <option>Suspension</option><option>Diagnostics</option><option>AC</option>
            <option>Bodywork</option><option>General repairs</option>
          </select></div>
        <div class="fld"><label for="f-road">Available for roadside assistance?</label>
          <select id="f-road" name="roadside">
            <option value="">Select one</option>
            <option>Yes — and I'm mobile with my own transport</option>
            <option>Yes — but I'm not mobile</option>
            <option>No</option>
          </select></div>
        <div class="fld"><label for="f-notes">Anything else we should know</label><textarea id="f-notes" name="notes"></textarea></div>
        <div style="position:absolute;left:-9999px" aria-hidden="true">
          <label for="company_website">Leave this empty</label>
          <input id="company_website" name="company_website" tabindex="-1" autocomplete="off">
        </div>
        <input type="hidden" name="form_type" value="provider">
        <input type="hidden" name="source" class="js-source" value="direct">
        <button class="btn btn--blue" type="submit" style="width:100%">Send my application</button>
        <p class="hint">Submitting sends your details to the Mechanic Connect team for review. It does not create an account or activate you on the platform. Apprenticeship opportunities are subject to availability and assessment.</p>
      </form>
    </div>
  </section>
</div>

<!-- ════════════ ABOUT ════════════ -->

<?php require __DIR__ . '/includes/footer.php'; ?>
