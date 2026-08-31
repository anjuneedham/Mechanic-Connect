<?php
$PAGE  = 'guide';
$TITLE = 'Free Guide: The Roadside Job Card — Mechanic Connect JA';
$DESC  = 'A free six-page guide for Jamaican drivers: what to check at the roadside, how to describe a fault, and the eight questions to ask before any mechanic starts work.';
require __DIR__ . '/includes/header.php';
?>

<section class="hero gears">
  <div class="wrap">
    <p class="kicker">Free guide &middot; 6 pages</p>
    <h1>Know what to ask before anyone opens your bonnet.</h1>
    <p class="lede">A small problem stays a small bill when you can describe the fault, read the quote, and spot a job going sideways. That is the whole guide &mdash; written for drivers, not mechanics.</p>
    <div class="btns">
      <a class="btn btn--white" href="#guideform">Send me the guide</a>
      <a class="btn btn--line" href="<?= wa('Hi, can you send me The Roadside Job Card guide?') ?>">Ask on WhatsApp</a>
    </div>
  </div>
</section>

<section class="sec">
  <div class="wrap">
    <div class="plate-h"><h2>What&rsquo;s inside</h2></div>
    <p class="lede" style="margin-top:13px">Six sections, no filler. Every page is something you can use at the roadside or standing in a workshop with your keys in your hand.</p>
    <ul class="checks">
      <li>The six-point check to run before you call anyone</li>
      <li>How to describe a fault so you are not guessed at</li>
      <li>Eight questions to ask before work starts</li>
      <li>How to read a quote, and what a fair one contains</li>
      <li>Parts: new, aftermarket or used, and what to ask for each</li>
      <li>Six signs the job is going sideways</li>
      <li>A fill-in vehicle card for your glovebox</li>
    </ul>
  </div>
</section>

<section class="sec mist">
  <div class="wrap">
    <div class="plate-h"><h2>Read section one free</h2></div>
    <p class="lede" style="margin-top:13px">Before you call anyone. Two minutes here changes the conversation: a mechanic who arrives to a clear description quotes on the fault, one who arrives to &ldquo;it just stopped&rdquo; quotes on the risk.</p>
    <ul class="checks">
      <li><strong>Get off the carriageway first.</strong> Hazards on, wheels turned away from traffic. Nothing below matters more than this.</li>
      <li><strong>Note the warning lights</strong> that are actually lit, and whether they came on before or after the trouble started. Photograph the cluster.</li>
      <li><strong>Check the obvious three:</strong> fuel gauge, temperature gauge, and whether the battery light is on.</li>
      <li><strong>Look underneath</strong> for anything dripping, and note the colour &mdash; clear, brown, red, green or black each mean something different.</li>
      <li><strong>Listen once more.</strong> Silence, a single click, rapid clicking and a slow crank are four different faults.</li>
      <li><strong>Note where you are</strong> in a way someone else can find: a landmark and a direction of travel beats a road name.</li>
    </ul>
    <div class="note">
      <p><strong>Record the noise</strong>A ten-second voice note taken while the fault is happening is worth more than any description. Send it with the job. The other five sections &mdash; describing the fault, the eight questions, reading a quote, the warning signs and the vehicle card &mdash; are in the PDF.</p>
    </div>
  </div>
</section>

<section class="sec" id="guideform">
  <div class="wrap">
    <div class="plate-h"><h2>Send me the guide</h2></div>
    <p class="lede" style="margin-top:13px">Tell us where you are and we will send it over. Free, and it stays free.</p>
    <form class="form" name="guide-download" method="POST" action="<?= BASE ?>/submit.php">
      <div class="fld"><label for="gd-name">Your name <span class="req">*</span></label><input id="gd-name" name="name" required></div>
      <div class="fld"><label for="gd-phone">WhatsApp or phone <span class="req">*</span></label><input id="gd-phone" name="phone" type="tel" required></div>
      <div class="fld">
        <label for="gd-parish">Parish</label>
        <select id="gd-parish" name="parish">
          <option value="">Choose your parish</option>
          <option>Clarendon</option><option>Hanover</option><option>Kingston</option>
          <option>Manchester</option><option>Portland</option><option>St. Andrew</option>
          <option>St. Ann</option><option>St. Catherine</option><option>St. Elizabeth</option>
          <option>St. James</option><option>St. Mary</option><option>St. Thomas</option>
          <option>Trelawny</option><option>Westmoreland</option>
        </select>
        <p class="hint">So we can point you at a verified mechanic near you when you need one.</p>
      </div>
      <div class="fld"><label for="gd-veh">Your vehicle</label><input id="gd-veh" name="vehicle" placeholder="e.g. 2012 Toyota Axio"><p class="hint">Optional.</p></div>
      <div style="position:absolute;left:-9999px" aria-hidden="true">
        <label for="company_website">Leave this empty</label>
        <input id="company_website" name="company_website" tabindex="-1" autocomplete="off">
      </div>
      <input type="hidden" name="form_type" value="guide">
      <input type="hidden" name="source" class="js-source" value="direct">
      <button class="btn btn--blue" type="submit" style="width:100%">Send me the guide</button>
      <p class="hint">The download opens on the next page. We use your details to send the guide and to put you in touch with a mechanic &mdash; nothing else, and we don&rsquo;t pass them on. See our <a href="<?= BASE ?>/privacy">privacy policy</a>.</p>
    </form>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
