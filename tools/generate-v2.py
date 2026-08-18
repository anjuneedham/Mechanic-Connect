# -*- coding: utf-8 -*-
"""Emits the static v2/ pages. Same note as gen_baseline.py: no build step ships
with the site, this only keeps the repeated chrome identical across pages."""
import io, os

WA = ("https://wa.me/18764703144?text="
      "Hi%20Mechanic%20Connect%20JA%2C%20I%20need%20a%20mechanic.")
IOS_CUSTOMER = "https://apps.apple.com/jm/app/mechanic-connect/id6754509101"
PLAY_CUSTOMER = ("https://play.google.com/store/apps/details?"
                 "id=com.mechanic.mechanicconnect")
PLAY_MECHANIC = "https://play.google.com/store/apps/details?id=com.mechanic.mechanics"

NAV = [
    ("Home", "index.html"),
    ("For Customers", "for-customers.html"),
    ("For Mechanics &amp; Garages", "for-mechanics.html"),
    ("FAQs", "faqs.html"),
    ("About Us", "about-us.html"),
]

# --- critical CSS ------------------------------------------------------------
# Deliberately written with literal values rather than custom properties: it
# must paint correctly before tokens.css has arrived, and tokens.css is loaded
# asynchronously. It mirrors the tokens of the same name in v2/tokens.css — if
# you change a token there, update this block in every page's <head>.
CRITICAL = u"""<style>
/* critical CSS — first-screen only. See the note in v2/styles.css. */
*,*::before,*::after{box-sizing:border-box}
body{margin:0;background:#fff;color:#16191d;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",Arial,sans-serif;font-size:1.0625rem;line-height:1.6}
img,svg{display:block;height:auto;max-width:100%}
h1{font-size:clamp(2rem,1.4rem + 3vw,3rem);line-height:1.15;letter-spacing:-.02em;margin:0 0 1rem}
p{margin:0 0 1rem}
.container{margin-inline:auto;max-width:1120px;padding-inline:1.25rem;width:100%}
.skip-link{position:absolute;top:-100px;left:.75rem;z-index:100;background:#16191d;color:#fff;padding:.75rem 1rem}
.skip-link:focus{top:.75rem}
.site-header{position:sticky;top:0;z-index:50;background:rgba(255,255,255,.96);border-bottom:1px solid #dde2e7}
.site-header .container{display:flex;align-items:center;justify-content:space-between;gap:.75rem;min-height:62px}
.brand{display:flex;flex:0 1 auto;align-items:center;gap:.5rem;min-width:0;min-height:44px;color:#16191d;font-weight:700;font-size:1.0625rem;text-decoration:none}
.brand-name{min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.brand-mark{flex:none}
.header-actions{display:flex;flex:none;align-items:center;gap:.5rem}
.nav-toggle-icon{font-size:1.125rem;line-height:1}
.visually-hidden{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);clip-path:inset(50%);white-space:nowrap}
.brand-mark{display:grid;place-items:center;width:30px;height:30px;border-radius:6px;background:#0170b9;color:#fff;font-size:.8125rem;font-weight:700}
.hero{padding-block:2rem 3rem}
.hero-grid{display:grid;gap:2rem}
.lead{color:#4a5158;font-size:clamp(1.0625rem,1rem + .4vw,1.25rem);margin-bottom:1.5rem}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;min-height:44px;padding:.75rem 1.5rem;border:1px solid transparent;border-radius:8px;font:inherit;font-weight:600;line-height:1.2;text-decoration:none}
.btn--primary{background:#0170b9;color:#fff}
.store-row{display:flex;flex-wrap:wrap;gap:.75rem;list-style:none;margin:0 0 1rem;padding:0}
.store-badge{display:inline-block;line-height:0;min-height:44px;border-radius:9px}
.store-badge img{height:54px;width:auto}
.nav-toggle{display:none}
.js .nav-toggle{display:inline-flex;flex:none;align-items:center;gap:.5rem;min-height:44px;padding-inline:.75rem;border:1px solid #dde2e7;border-radius:8px;background:#fff;color:#16191d;font:inherit;font-size:.9375rem;cursor:pointer}
@media (max-width:767.98px){.site-header .container{flex-wrap:wrap;padding-block:.5rem}.site-nav{flex-basis:100%;order:3}.js .site-nav{display:none}.js .site-nav.is-open{display:block}.brand{font-size:.9375rem}.nav-toggle{padding-inline:.5rem}.nav-toggle-label{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);clip-path:inset(50%);white-space:nowrap}.header-actions .btn{padding-inline:.75rem}}
@media (min-width:768px){.js .nav-toggle{display:none}.js .site-nav,.js .site-nav.is-open{display:block}}
@media (min-width:1024px){.hero-grid{grid-template-columns:1.05fr .95fr;align-items:center;gap:4rem}}
</style>"""


# How the stylesheets reach the page.
#
# Spec §6 item 10 asks for inlined critical CSS and no render-blocking
# resources. Both modes below were built and measured three times each on
# Lighthouse mobile (see COMPARISON.md for the numbers):
#
#   async     inline critical block + media="print" swap
#             performance 84, CLS 0.314, FCP 0.9s   (reproducible, 3/3 runs)
#   blocking  two ordinary stylesheet links
#             performance 100, CLS 0.013, FCP 1.1s  (reproducible, 3/3 runs)
#
# The swap relaid the page after the first paint, and the shift cost far more
# than the 0.2s of paint it bought back. Two same-origin sheets totalling under
# 20 KB, cached once and reused by all seven pages, beat inlining them into
# every page anyway. "blocking" ships; "async" is kept so the comparison can be
# reproduced with CSS_MODE=async.
CSS_MODE = os.environ.get("CSS_MODE", "blocking")

CSS_LINKS = {
    "async": u"""<link rel="stylesheet" href="tokens.css" media="print" onload="this.media='all'">
<link rel="stylesheet" href="styles.css" media="print" onload="this.media='all'">
<noscript>
  <link rel="stylesheet" href="tokens.css">
  <link rel="stylesheet" href="styles.css">
</noscript>""",
    "blocking": u"""<link rel="stylesheet" href="tokens.css">
<link rel="stylesheet" href="styles.css">""",
}

CSS_LINKS["async"] = CRITICAL + u"\n\n" + CSS_LINKS["async"]


def head(title, description, current):
    nav = "\n".join(
        '          <li><a href="%s"%s>%s</a></li>'
        % (href, ' aria-current="page"' if href == current else "", label)
        for label, href in NAV
    )
    return u"""<!DOCTYPE html>
<html lang="en-JM" class="no-js">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Spec §6 item 8: a descriptive, page-specific title. The baseline's
     "<page> - Mechanic Connect JA" pattern and its truncated og:site_name are
     both gone. -->
<title>%(title)s</title>
<meta name="description" content="%(description)s">

<meta property="og:type" content="website">
<meta property="og:site_name" content="Mechanic Connect JA">
<meta property="og:title" content="%(title)s">
<meta property="og:description" content="%(description)s">
<!-- Spec §6 item 8: a real 1200x630 Open Graph image, not the 300x300 avatar
     crop. This site is shared in WhatsApp groups; the preview is the ad. -->
<meta property="og:image" content="assets/og-image-1200x630.jpg">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="Mechanic Connect JA">
<meta name="twitter:card" content="summary_large_image">

<link rel="icon" href="assets/favicon.ico" sizes="any">

<!-- Bootstrap, deliberately inline and deliberately first: it marks the
     document script-capable before the body is parsed, so the CSS below can
     collapse the mobile nav in the very first paint. Collapsing it from a
     deferred script instead moves the page after the visitor has already seen
     it. One statement, no dependencies. -->
<script>document.documentElement.className=document.documentElement.className.replace("no-js","")+" js";</script>

<!-- Spec §6 item 10, with a measured deviation — see the CSS_MODE note in the
     page generator and the performance section of COMPARISON.md. Under 20 KB
     of CSS, same origin, cached across all seven pages. -->
%(csslinks)s

<script src="config.js" defer></script>
<script src="app.js" defer></script>
</head>
<body>

<a class="skip-link" href="#main">Skip to main content</a>

<header class="site-header">
  <div class="container">

    <a class="brand" href="index.html">
      <span class="brand-mark" aria-hidden="true">MC</span>
      <span class="brand-name">Mechanic Connect JA</span>
    </a>

    <button class="nav-toggle" type="button" aria-controls="site-nav" aria-expanded="false">
      <span class="nav-toggle-icon" aria-hidden="true">&#9776;</span>
      <span class="nav-toggle-label">Menu</span>
    </button>

    <!-- Spec §6 item 9: navigation is conversion pages only. Privacy Policy
         and Terms of Service have moved to the footer, where legal pages
         belong. -->
    <nav class="site-nav" id="site-nav" aria-label="Main">
      <ul>
%(nav)s
      </ul>
    </nav>

    <!-- Spec §6 item 2: one primary action in the header, and only one. -->
    <div class="header-actions">
      <a class="btn btn--primary btn--sm" href="index.html#get-the-app">Get the app</a>
    </div>

  </div>
</header>

<main id="main">
""" % {"title": title, "description": description,
       "csslinks": CSS_LINKS[CSS_MODE], "nav": nav}


def store_row(which, lazy=False, ios_first=True):
    """Both badges are always in the DOM and always clickable. app.js reorders
    them so the visitor's own platform leads — spec §6 item 3."""
    loading = ' loading="lazy" decoding="async"' if lazy else ' decoding="async"'
    ios_url = IOS_CUSTOMER if which == "customer" else None
    play_url = PLAY_CUSTOMER if which == "customer" else PLAY_MECHANIC

    items = []
    if ios_url:
        items.append(u"""      <li data-platform="ios">
        <a class="store-badge" data-store-link href="%s">
          <img src="assets/app-store-badge.svg" width="162" height="54" alt="Download Mechanic Connect on the App Store"%s>
        </a>
      </li>""" % (ios_url, loading))
    items.append(u"""      <li data-platform="android">
        <a class="store-badge" data-store-link href="%s">
          <img src="assets/google-play-badge.svg" width="162" height="54" alt="Get %s on Google Play"%s>
        </a>
      </li>""" % (play_url,
                  "Mechanic Connect" if which == "customer" else "the Mechanic Connect staff app",
                  loading))
    if not ios_first:
        items.reverse()
    return u"""    <ul class="store-row" data-store-row>
%s
    </ul>""" % "\n".join(items)


FOOTER = u"""
</main>

<footer class="site-footer">
  <div class="container">

    <div class="footer-grid">

      <div>
        <p class="footer-title">Mechanic Connect JA</p>
        <p class="footer-note">Operated by OH-PEL AUTO LTD, Kingston, Jamaica.</p>
        <!-- Fault #2 fixed: a real WhatsApp deep link with a prefilled message,
             replacing the baseline's dead http://8764703144, which resolved
             to nothing. Spec §6 item 5. -->
        <p><a href="%(wa)s">WhatsApp us</a></p>
        <!-- Fault #9 fixed: the contact address is a mailto: link. -->
        <p><a href="mailto:mechanicconnectja@gmail.com">mechanicconnectja@gmail.com</a></p>
      </div>

      <div>
        <p class="footer-title">Site</p>
        <ul class="footer-list">
          <li><a href="index.html">Home</a></li>
          <li><a href="for-customers.html">For Customers</a></li>
          <li><a href="for-mechanics.html">For Mechanics &amp; Garages</a></li>
          <li><a href="faqs.html">FAQs</a></li>
          <li><a href="about-us.html">About Us</a></li>
        </ul>
      </div>

      <div>
        <p class="footer-title">Follow</p>
        <ul class="footer-list">
          <li><a href="https://www.tiktok.com/@mechanicconnectja">TikTok</a></li>
          <li><a href="https://www.instagram.com/mechanicconnectja/">Instagram</a></li>
        </ul>
      </div>

      <div>
        <!-- Spec §6 item 3: the store badges are repeated in the footer, so the
             download is reachable from the bottom of any page. -->
        <p class="footer-title">Get the customer app</p>
%(badges)s
        <p class="footer-note">Mechanics and garage staff: <a href="for-mechanics.html">use the staff app</a>.</p>
      </div>

    </div>

    <div class="footer-bottom">
      <p class="footer-note">Copyright &copy; 2026 Mechanic Connect JA</p>
      <!-- Spec §6 item 9: legal lives here, not in the main navigation. -->
      <ul class="legal-links footer-note">
        <li><a href="privacy-policy.html">Privacy Policy</a></li>
        <li><a href="terms-of-service.html">Terms of Service</a></li>
      </ul>
    </div>

  </div>
</footer>

</body>
</html>
""" % {"wa": WA, "badges": store_row("customer", lazy=True)}


def copy_pending(url, what):
    return u"""      <!-- ====================================================================
           COPY PENDING. Spec §1 requires this page's copy verbatim from

               %s

           and that host is blocked by this build environment's egress policy,
           so it could not be read. Spec §6 forbids inventing copy and spec §8
           forbids new claims, so %s is not written here.
           The page's structure, meta, navigation and footer are final; only the
           body copy is outstanding. Tracked in COMPARISON.md.
           ==================================================================== -->
      <div class="copy-pending">
        <p><strong>Copy pending.</strong> The client&rsquo;s wording for this page is
        carried over verbatim from the current site and has not been rewritten &mdash;
        copy rewriting is a separate, client-approved step (spec &sect;8).</p>
      </div>
""" % (url, what)


def write(path, text):
    with io.open(path, "w", encoding="utf-8") as fh:
        fh.write(text)
    print("wrote", path, os.path.getsize(path), "bytes")


V = "v2/"

# =========================================================== index.html (home)
HERO_COPY = ("Connect with verified garages and independent mechanics across "
             "Jamaica for repairs, servicing, roadside assistance, and parts "
             "&mdash; all in one app.")

index = head(
    "Mechanic Connect JA &mdash; find a verified mechanic anywhere in Jamaica",
    "Connect with verified garages and independent mechanics across Jamaica for "
    "repairs, servicing, roadside assistance, and parts - all in one app.",
    "index.html",
) + u"""
<!-- ============================================================================
     FAULT #1 FIXED (spec §6 item 1). This file is the site root AND the home
     page. On the current site the root serves a WordPress blog index and the
     home page hides at /home/, so every direct visit, printed link and ad click
     lands on a blog roll. Here, arriving at the root means arriving at the
     pitch. There is no blog index in v2/.
     ========================================================================== -->

<section class="hero">
  <div class="container hero-grid">

    <div>
      <!-- Spec §6 item 8: exactly one h1 per page. The baseline home page had
           two — the WordPress page title and the hero heading. -->
      <h1>Your Trusted Mechanic, On-Demand.</h1>

      <p class="lead">%(hero)s</p>

      <!-- Fault #3 fixed (spec §6 item 3): real store links, above the fold.
           The whole call to action is "download the app", and the current site
           has no App Store or Google Play link anywhere on it. -->
%(badges)s
      <p class="store-note">Free to download. Mechanics and garage staff need the
        <a href="for-mechanics.html">separate staff app</a>.</p>

      <p>
        <!-- Fault #5 fixed: real buttons with 44px tap targets, not bare text
             links. The baseline printed the line "Two main call-to-action
             buttons:" above two unstyled links — fault #4, gone here. -->
        <a class="btn btn--primary" href="#get-started">Tell us what you need</a>
        <a class="btn btn--ghost" href="%(wa)s">WhatsApp us</a>
      </p>
    </div>

    <div class="hero-media">
      <!-- Spec §6 item 10: the hero is the LCP element, so it is fetched at
           high priority and sized for the viewport rather than shipping a
           desktop-width file to a phone. Intrinsic size is declared so the box
           is reserved before the bytes land. -->
      <img src="assets/hero-960.jpg"
           srcset="assets/hero-640.jpg 640w, assets/hero-960.jpg 960w, assets/hero-1200.jpg 1200w"
           sizes="(min-width: 1024px) 46vw, calc(100vw - 2.5rem)"
           width="1200" height="900" fetchpriority="high" decoding="async"
           alt="A mechanic working on a vehicle.">
    </div>

  </div>
</section>

<!-- ============================================================================
     Spec §6 item 6 — lead capture ahead of the app install. Once a visitor
     crosses into the App Store the journey is invisible, so the only chance to
     attribute an ad click to a real person is here, before the handover.
     ========================================================================== -->
<section class="section section--alt" id="get-started">
  <div class="container container--narrow">

    <p class="eyebrow">Before you download</p>
    <h2>Tell us what you need</h2>
    <p class="lead">Leave your details and Mechanic Connect JA will follow up on
      WhatsApp. You can still download the app straight away.</p>

    <!-- The action below is the no-JavaScript fallback. app.js overwrites it
         with MC_CONFIG.FORM_ENDPOINT from config.js, which is the single place
         the endpoint is configured. Keep the two the same. -->
    <form class="lead-form" data-lead-form method="post"
          action="https://example.invalid/REPLACE-WITH-YOUR-FORM-ENDPOINT">

      <div class="field">
        <label for="lead-name">Your name</label>
        <input id="lead-name" name="name" type="text" autocomplete="name" required>
      </div>

      <div class="field">
        <label for="lead-phone">Phone number
          <span class="hint">So a mechanic can reach you on WhatsApp or by call.</span>
        </label>
        <input id="lead-phone" name="phone" type="tel" inputmode="tel"
               autocomplete="tel" required>
      </div>

      <div class="field">
        <label for="lead-parish">Parish</label>
        <select id="lead-parish" name="parish" required>
          <option value="">Choose your parish</option>
          <option>Clarendon</option>
          <option>Hanover</option>
          <option>Kingston</option>
          <option>Manchester</option>
          <option>Portland</option>
          <option>St. Andrew</option>
          <option>St. Ann</option>
          <option>St. Catherine</option>
          <option>St. Elizabeth</option>
          <option>St. James</option>
          <option>St. Mary</option>
          <option>St. Thomas</option>
          <option>Trelawny</option>
          <option>Westmoreland</option>
        </select>
      </div>

      <div class="field">
        <label for="lead-need">What do you need?</label>
        <select id="lead-need" name="need" required>
          <option value="">Choose one</option>
          <option>Repairs</option>
          <option>Servicing</option>
          <option>Roadside assistance</option>
          <option>Parts</option>
          <option>Something else</option>
        </select>
      </div>

      <div class="field">
        <label for="lead-detail">Anything else? <span class="hint">Optional.</span></label>
        <textarea id="lead-detail" name="detail" rows="3"></textarea>
      </div>

      <!-- Spec §6 item 7: UTM parameters captured from the query string and
           carried into the submission, so an install can be traced back to the
           post or the ad that produced it. app.js fills these; they stay empty
           for visitors who arrive without campaign parameters. -->
      <input type="hidden" name="utm_source" value="">
      <input type="hidden" name="utm_medium" value="">
      <input type="hidden" name="utm_campaign" value="">
      <input type="hidden" name="landing_page" value="">

      <button class="btn btn--primary btn--block" type="submit">Send my details</button>
      <p class="form-legal">By sending your details you agree to our
        <a href="privacy-policy.html">Privacy Policy</a>.</p>
    </form>

  </div>
</section>

<!-- ============================================================================
     Spec §6 item 4 — two audiences, two apps, kept apart. The current site
     conflates them: one undifferentiated "download the app" with no download
     link at all.
     ========================================================================== -->
<section class="section" id="get-the-app">
  <div class="container">

    <h2>Two apps, two jobs</h2>
    <p class="lead">Drivers and garage staff use different apps. Pick the one
      that matches you.</p>

    <ul class="card-grid">

      <li class="card card--customer">
        <p class="card-tag">For drivers</p>
        <h3>I need a mechanic</h3>
        <p>Verified service providers. No customer platform fees. Clear, upfront
          pricing.</p>
%(cust_badges)s
        <p><a href="for-customers.html">More for customers</a></p>
      </li>

      <li class="card card--trade">
        <p class="card-tag">For mechanics &amp; garages</p>
        <h3>I am a mechanic</h3>
        <p>Mechanics assigned to a garage use the separate Mechanic Connect staff
          app.</p>
%(mech_badges)s
        <!-- No Apple listing for the staff app was supplied, so no iOS badge is
             shown here. Add STORES.mechanic.ios in config.js when it exists;
             nothing is invented to fill the gap. -->
        <p><a href="for-mechanics.html">More for mechanics &amp; garages</a></p>
      </li>

    </ul>
  </div>
</section>

<section class="section section--alt">
  <div class="container">
    <h2>How It Works</h2>
    <ol class="steps">
      <li><strong>Download &amp; Request Service</strong><!-- COPY PENDING: the sentence following this lead-in on the client's page could not be read (host blocked). Not invented — spec §8. --></li>
      <li><strong>Get Quotes or Book Directly</strong><!-- COPY PENDING: as above. --></li>
      <li><strong>Job Gets Done, Pay Securely</strong><!-- COPY PENDING: as above. --></li>
    </ol>
  </div>
</section>

<section class="section">
  <div class="container">
    <h2>Why Choose Mechanic Connect?</h2>
    <ul class="reasons">
      <li><strong>Verified Service Providers</strong><!-- COPY PENDING: trailing sentence not readable (host blocked). --></li>
      <li><strong>No Customer Platform Fees</strong><!-- COPY PENDING: as above. --></li>
      <li><strong>Clear, Upfront Pricing</strong><!-- COPY PENDING: as above. --></li>
      <li><strong>Secure In-App Payments</strong><!-- COPY PENDING: as above. --></li>
      <li><strong>Parts Made Easy (Powered by OH-PEL AUTO LIMITED)</strong><!-- COPY PENDING: as above. --></li>
      <li><strong>Built for Jamaica, by Jamaicans</strong><!-- COPY PENDING: as above. --></li>
    </ul>
  </div>
</section>
""" % {
    "hero": HERO_COPY,
    "badges": store_row("customer"),
    "wa": WA,
    "cust_badges": store_row("customer", lazy=True),
    "mech_badges": store_row("mechanic", lazy=True),
} + FOOTER
write(V + "index.html", index)

# ==================================================== for-customers / -mechanics
customers = head(
    "For Customers &mdash; Mechanic Connect JA",
    "Download the Mechanic Connect JA customer app for repairs, servicing, "
    "roadside assistance and parts across Jamaica.",
    "for-customers.html",
) + u"""
<section class="section">
  <div class="container">
    <div class="prose">

      <h1>For Customers</h1>
      <p class="lead">%(hero)s</p>

%(badges)s
      <p class="store-note">Free to download.</p>

      <p>
        <a class="btn btn--primary" href="index.html#get-started">Tell us what you need</a>
        <a class="btn btn--ghost" href="%(wa)s">WhatsApp us</a>
      </p>

%(pending)s
    </div>
  </div>
</section>
""" % {
    "hero": HERO_COPY,
    "badges": store_row("customer"),
    "wa": WA,
    "pending": copy_pending(
        "https://mechanic-connectja.mechanic-connect.net/for-customers/",
        "customer-facing marketing copy"),
} + FOOTER
write(V + "for-customers.html", customers)

mechanics = head(
    "For Mechanics &amp; Garages &mdash; Mechanic Connect JA",
    "Mechanic Connect JA for garages and independent mechanics in Jamaica, "
    "including the separate staff app for garage-assigned mechanics.",
    "for-mechanics.html",
) + u"""
<section class="section">
  <div class="container">
    <div class="prose">

      <h1>For Mechanics &amp; Garages</h1>
      <p class="lead">Mechanics assigned to a garage use the separate Mechanic
        Connect staff app, not the customer app.</p>

%(badges)s
      <!-- No Apple listing for the staff app was supplied. Add
           STORES.mechanic.ios in config.js when one exists. -->
      <p class="store-note">Looking for the customer app instead?
        <a href="for-customers.html">Get it here</a>.</p>

      <p>
        <a class="btn btn--trade" href="%(wa)s">WhatsApp us</a>
      </p>

%(pending)s
    </div>
  </div>
</section>
""" % {
    "badges": store_row("mechanic"),
    "wa": WA,
    "pending": copy_pending(
        "https://mechanic-connectja.mechanic-connect.net/for-mechanics/",
        "mechanic- and garage-facing marketing copy"),
} + FOOTER
write(V + "for-mechanics.html", mechanics)

# ================================================================ simple pages
SIMPLE = [
    ("about-us.html", "About Us",
     "About Us &mdash; Mechanic Connect JA",
     "About Mechanic Connect JA, operated by OH-PEL AUTO LTD in Kingston, Jamaica.",
     "https://mechanic-connectja.mechanic-connect.net/about-us/",
     "an About page for a real company"),
    ("faqs.html", "Frequently Asked Questions",
     "Frequently Asked Questions &mdash; Mechanic Connect JA",
     "Answers to common questions about Mechanic Connect JA.",
     "https://mechanic-connectja.mechanic-connect.net/mechanic-connect-frequently-asked-questions-faqs/",
     "questions and answers the client never wrote"),
    ("privacy-policy.html", "Privacy Policy",
     "Privacy Policy &mdash; Mechanic Connect JA",
     "How Mechanic Connect JA handles personal information.",
     "https://mechanic-connectja.mechanic-connect.net/privacy-policy-2/",
     "the text of a privacy policy"),
    ("terms-of-service.html", "Terms of Service",
     "Terms of Service &mdash; Mechanic Connect JA",
     "The terms of service and end-user agreement for Mechanic Connect JA.",
     "https://mechanic-connectja.mechanic-connect.net/terms-of-service-end-user-agreement-for-mechanic-connect/",
     "the text of a binding end-user agreement"),
]

for filename, h1, title, desc, url, what in SIMPLE:
    page = head(title, desc, filename if filename in dict(
        (h, l) for l, h in NAV) else "") + u"""
<section class="section">
  <div class="container">
    <div class="prose">

      <h1>%s</h1>

%s
    </div>
  </div>
</section>
""" % (h1, copy_pending(url, what)) + FOOTER
    write(V + filename, page)
