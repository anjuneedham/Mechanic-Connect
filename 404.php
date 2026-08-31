<?php
http_response_code(404);
$PAGE = ''; $TITLE = 'Page not found — Mechanic Connect JA'; $DESC = 'That page does not exist.';
require __DIR__ . '/includes/header.php';
?>
<section class="hero gears">
  <div class="wrap">
    <p class="kicker">404</p>
    <h1>That page isn't here.</h1>
    <p class="lede">The link may be old or mistyped. Pick what you need below, or message us and we'll point you the right way.</p>
    <div class="btns">
      <a class="btn btn--red" href="/roadside">Get roadside help</a>
      <a class="btn btn--white" href="/">Back to home</a>
    </div>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
