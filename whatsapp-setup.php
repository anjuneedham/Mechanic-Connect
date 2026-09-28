<?php
$PAGE    = '';
$NOINDEX = true;   // a working page for the client, not for search or the nav
$TITLE   = 'WhatsApp Business API setup — Mechanic Connect JA';
$DESC    = 'What Mechanic Connect needs to share so the enquiry agent can answer on WhatsApp.';
require __DIR__ . '/includes/header.php';
?>

<section class="hero gears">
  <div class="wrap">
    <p class="kicker">Enquiry agent</p>
    <h1>Connect WhatsApp to the enquiry agent.</h1>
    <p class="lede">The agent is built. The one thing it is waiting on is WhatsApp Business API access on a Mechanic Connect account. This page lists what to set up and what to send back. It takes about an hour, plus Meta's business verification time.</p>
  </div>
</section>

<section class="sec">
  <div class="wrap" style="max-width:800px">
    <div class="plate-h"><h2>Before you start</h2></div>
    <ul class="checks" style="grid-template-columns:1fr">
      <li>Someone who is an admin on the Mechanic Connect Facebook / Meta Business account</li>
      <li>OH-PEL AUTO LTD's business details: legal name, address, phone, website, and registration documents if Meta asks for them</li>
      <li>A phone number for the agent (see the note below before you choose one)</li>
    </ul>
    <div class="note">
      <p><strong>Choose the number carefully</strong>A phone number can be used either in the regular WhatsApp or WhatsApp Business app, or on the API, and moving one onto the API can take it out of the app. If 876-470-3144 is your live customer line, do not move it until you have confirmed how Meta handles that for your account. A separate number for the agent is the safe choice.</p>
    </div>

    <div class="plate-h" style="margin-top:36px"><h2>Steps</h2></div>
    <ol class="steps">
      <li><strong>Set up Meta Business.</strong> Go to business.facebook.com and create or open the Mechanic Connect business account.</li>
      <li><strong>Verify the business.</strong> In Business Settings → Security Center, start business verification with OH-PEL AUTO LTD's details. This is the step that takes time, and Meta controls how long.</li>
      <li><strong>Create a developer app.</strong> At developers.facebook.com, create an app of type Business and add the WhatsApp product to it.</li>
      <li><strong>Add the phone number.</strong> Under WhatsApp → API Setup, add and verify the number by the code Meta sends.</li>
      <li><strong>Create a permanent access token.</strong> In Business Settings → Users → System Users, create a system user, give it access to the app and the WhatsApp account, and generate a token with the whatsapp_business_messaging and whatsapp_business_management permissions. The temporary token on the API Setup page expires within a day, so do not use that one.</li>
    </ol>

    <div class="plate-h" style="margin-top:36px"><h2>What to send back</h2></div>
    <ul class="checks" style="grid-template-columns:1fr">
      <li>Phone number ID</li>
      <li>WhatsApp Business Account ID</li>
      <li>The permanent access token</li>
    </ul>
    <div class="note">
      <p><strong>Do not send the token by email or WhatsApp message</strong>The token gives full control of the number. Share it by a password manager link, or read it out on a call and have it typed in directly. If it is ever sent in plain text, generate a new one afterwards.</p>
    </div>
    <p style="margin-top:18px">Once the three items are shared, the agent goes live within one week. Meta's screens and permission names change from time to time. If a step looks different, send a screenshot and we will work from that.</p>
    <div class="btns">
      <a class="btn btn--blue" href="<?= wa('Hi, I have a question about the WhatsApp Business API setup') ?>">Ask a question on WhatsApp</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
