# -*- coding: utf-8 -*-
"""Emits the static baseline/ pages. No build step ships with the site — this
script exists only so the repeated Astra chrome stays byte-identical across the
eight pages. The committed .html files are plain, self-contained HTML."""
import io, os

NAV = [
    ("About Us", "about-us.html"),
    ("FAQs", "faqs.html"),
    ("For Customers", "for-customers.html"),
    ("For Mechanics &amp; Garages", "for-mechanics.html"),
    (u"Mechanic Connect – Home", "home.html"),
    ("Privacy Policy", "privacy-policy.html"),
    ("Terms of Service", "terms-of-service.html"),
]


def nav_items(indent):
    pad = " " * indent
    return "\n".join(
        '%s<li class="menu-item"><a href="%s">%s</a></li>' % (pad, href, label)
        for label, href in NAV
    )


def head(title, extra_meta="", body_class=""):
    return u"""<!DOCTYPE html>
<html lang="en-US" class="no-js">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>%(title)s</title>
%(extra)s<link rel="icon" href="assets/favicon.ico" sizes="any">
<link rel="stylesheet" href="tokens.css">
<link rel="stylesheet" href="styles.css">
<script src="nav.js"></script>
</head>
<body class="%(bodyclass)s">
""" % {"title": title, "extra": extra_meta, "bodyclass": body_class}


HEADER = u"""
<header class="site-header">
  <div class="container">

    <div class="site-branding">
      <p class="site-title"><a href="index.html">Mechanic Connect JA</a></p>
    </div>

    <!-- FAULT (reproduced deliberately, spec §3 item 1): the live site outputs
         the same menu twice — once for desktop, once for mobile. Both copies
         are here; a media query decides which one is visible. Every nav label
         therefore appears twice in the DOM. Do not de-duplicate in baseline. -->
    <nav class="main-navigation" aria-label="Site Navigation">
      <ul class="nav-menu">
%(desktop)s
      </ul>
    </nav>

    <button class="menu-toggle" type="button" aria-controls="mobile-menu" aria-expanded="false">Menu</button>

    <nav class="mobile-navigation" id="mobile-menu" aria-label="Mobile Site Navigation">
      <ul class="nav-menu">
%(mobile)s
      </ul>
    </nav>

  </div>
</header>
""" % {"desktop": nav_items(8), "mobile": nav_items(8)}


FOOTER = u"""
<footer class="site-footer">
  <div class="container">

    <p class="footer-links"><a href="for-customers.html">For Customers</a> | <a href="for-mechanics.html">For Mechanics</a> | <a href="about-us.html">About Us</a> | <a href="faqs.html">FAQ</a></p>

    <!-- FAULT #2 (reproduced deliberately, spec §3): the WhatsApp href is
         "http://8764703144". That is not a URL — the browser resolves it as a
         host name and the link opens nothing. The primary contact route on the
         site is dead. Fixed in v2/ as https://wa.me/18764703144. -->
    <p class="footer-social"><a href="http://8764703144">WhatsApp</a> | <a href="https://www.tiktok.com/@mechanicconnectja">TikTok</a> | <a href="https://www.instagram.com/mechanicconnectja/">Instagram</a></p>

    <!-- FAULT #9 (reproduced deliberately): the contact address is plain text,
         not a mailto: link. On mobile it cannot be tapped to compose. -->
    <p class="footer-contact">Contact: mechanicconnectja@gmail.com</p>

    <p class="footer-copyright">Copyright &copy; 2026 Mechanic Connect JA | Powered by Astra WordPress Theme</p>

  </div>
</footer>

<a id="ast-scroll-top" href="#" aria-label="Scroll to Top">Scroll to Top</a>

</body>
</html>
"""


def copy_pending(url, what):
    return u"""    <!-- ====================================================================
         COPY PENDING — nothing has been written here on purpose.

         Spec §1: "Fetch every page and extract the copy verbatim. Do not
         paraphrase, do not fix typos, do not improve headlines." The source
         page is

             %s

         and that host is blocked by this build environment's network egress
         policy (the proxy answers 403 to CONNECT). The copy could not be read.

         Inventing %s would break spec §1 and spec §8, so this
         region is left empty rather than filled with plausible-looking text.
         Paste the live page's exact markup between these two markers; the
         surrounding Astra chrome, tokens and stylesheet are already correct.

         Tracked in COMPARISON.md → "Could not be determined from the live site".
         ==================================================================== -->
""" % (url, what)


def write(path, text):
    with io.open(path, "w", encoding="utf-8") as fh:
        fh.write(text)
    print("wrote", path, os.path.getsize(path), "bytes")


B = "baseline/"

# ---------------------------------------------------------------- home.html --
home_meta = u"""<!-- FAULT #7 (reproduced deliberately): og:site_name ends in a bare hyphen
     with no descriptor, so every share preview reads "Mechanic Connect JA -".
     FAULT #8 (reproduced deliberately): og:image points at the 300x300 avatar
     crop. Open Graph wants 1200x630; a 300x300 square previews badly in
     WhatsApp and Instagram, which is where this site's traffic actually is. -->
<meta property="og:site_name" content="Mechanic Connect JA -">
<meta property="og:image" content="assets/cropped-IMG-20250620-WA0006-300x300.jpg">
<meta property="og:type" content="website">
<meta property="og:title" content="Mechanic Connect &#8211; Home">
<!-- ASSUMED: og:type and og:title above are the values WordPress emits by
     default. They were not read from the live site — see COMPARISON.md. -->
"""

home = head(u"Mechanic Connect – Home - Mechanic Connect JA", home_meta, "page home") + HEADER + u"""
<main class="site-content">
  <div class="container">
    <article class="entry">

      <!-- FAULT #6 part 1 of 2 (reproduced deliberately): this is the first of
           two <h1> elements on the page — the WordPress page title. -->
      <h1 class="entry-title">Mechanic Connect &#8211; Home</h1>

      <!-- FAULT #6 part 2 of 2: the hero heading is also an <h1>. Two top-level
           headings on one page leaves no single subject for a search engine to
           attach to. Do not demote either one in baseline. -->
      <h1>Your Trusted Mechanic, On-Demand.</h1>

      <p><img class="native-size" src="assets/cropped-IMG-20250620-WA0006-300x300.jpg" width="300" height="300" alt="" decoding="async"></p>

      <p>Connect with verified garages and independent mechanics across Jamaica for repairs, servicing, roadside assistance, and parts &#8212; all in one app.</p>

      <!-- FAULT #4 (reproduced deliberately, spec §3 item 6): the line below is
           build-instruction copy that was left in the published page. It is
           visible to every customer who lands here. Reproduce it exactly. -->
      <p>Two main call-to-action buttons:</p>

      <!-- FAULT #5 (reproduced deliberately): the two calls to action are plain
           text links. No button, no background, no padding, no tap target.
           Replicate as plain links — styling them here would be an improvement,
           and improvements do not belong in baseline. -->
      <p><a href="for-customers.html">I Need a Mechanic</a></p>
      <p><a href="for-mechanics.html">I Am a Mechanic</a></p>

      <h2>How It Works</h2>
      <ol>
        <li><strong>Download &amp; Request Service</strong><!-- COPY PENDING: the sentence that follows this bolded lead-in on the live page could not be read (host blocked). Not invented. --></li>
        <li><strong>Get Quotes or Book Directly</strong><!-- COPY PENDING: as above. --></li>
        <li><strong>Job Gets Done, Pay Securely</strong><!-- COPY PENDING: as above. --></li>
      </ol>

      <h2>Why Choose Mechanic Connect?</h2>
      <ul>
        <li><strong>Verified Service Providers</strong><!-- COPY PENDING: trailing sentence not readable (host blocked). Not invented. --></li>
        <li><strong>No Customer Platform Fees</strong><!-- COPY PENDING: as above. --></li>
        <li><strong>Clear, Upfront Pricing</strong><!-- COPY PENDING: as above. --></li>
        <li><strong>Secure In-App Payments</strong><!-- COPY PENDING: as above. --></li>
        <li><strong>Parts Made Easy (Powered by OH-PEL AUTO LIMITED)</strong><!-- COPY PENDING: as above. --></li>
        <li><strong>Built for Jamaica, by Jamaicans</strong><!-- COPY PENDING: as above. --></li>
      </ul>

      <!-- FAULT #3 (reproduced deliberately): the entire page asks the visitor
           to download an app, and there is no App Store or Google Play link
           anywhere on it — or anywhere on the site. Nothing is added here. -->

    </article>
  </div>
</main>
""" + FOOTER
write(B + "home.html", home)

# --------------------------------------------------------------- index.html --
# FAULT #1, the centrepiece (spec §4): the root of the live domain does NOT
# serve the home page. It serves the WordPress blog index. baseline/index.html
# must therefore be the blog roll, so that opening the replica lands you exactly
# where a real visitor lands.
index_meta = u"""<meta property="og:site_name" content="Mechanic Connect JA -">
<meta property="og:type" content="website">
"""

index = head(u"Mechanic Connect JA -", index_meta, "blog home") + HEADER + u"""
<main class="site-content">
  <div class="container">

    <!-- ====================================================================
         FAULT #1 — THE ROUTING FAULT (spec §4). Reproduced deliberately.

         This file is the root of the replica, and it is a blog index, not the
         home page. Every direct visit, every printed link, every "search the
         name" arrival and every ad click lands on this blog roll. The actual
         home page is one directory further in, at home.html.

         This is the single most important thing in the replica. Do not point
         index.html at the home page in baseline/. In v2/, index.html IS the
         home page — that is the fix.
         ==================================================================== -->

    <div class="content-area">

      <div class="site-main">

        <article class="post">
          <h2 class="entry-title"><a href="index.html#post-1">Mechanic Connect JA!</a></h2>

          <p class="entry-meta">
            <span class="posted-on"><time datetime="2025-11-05">November 5, 2025</time></span>
            <span class="byline"> / By <a href="index.html#author">Mechanic Connect JA</a></span>
          </p>

""" + copy_pending(
    "https://mechanic-connectja.mechanic-connect.net/",
    "the body of the client's welcome post",
) + u"""
          <p class="entry-footer"><a href="index.html#respond">Leave a Comment</a> / <a href="index.html#category">Uncategorized</a></p>
        </article>

      </div>

      <!-- ASSUMED: the widget set below is stock WordPress (Search, Recent
           Posts, Recent Comments, Archives, Categories, Meta). The live
           sidebar could not be read — see COMPARISON.md. The structural point
           of the demo does not depend on which widgets these are: what matters
           is that the root URL serves a blog chrome at all. -->
      <aside class="widget-area">

        <section class="widget widget_search">
          <form class="search-form" role="search" method="get" action="index.html">
            <label class="screen-reader-text" for="s">Search for:</label>
            <input type="search" id="s" name="s" placeholder="Search &hellip;">
            <input type="submit" value="Search">
          </form>
        </section>

        <section class="widget widget_recent_entries">
          <h2 class="widget-title">Recent Posts</h2>
          <ul>
            <li><a href="index.html#post-1">Mechanic Connect JA!</a></li>
          </ul>
        </section>

        <section class="widget widget_recent_comments">
          <h2 class="widget-title">Recent Comments</h2>
          <ul>
            <li>No comments to show.</li>
          </ul>
        </section>

        <section class="widget widget_archive">
          <h2 class="widget-title">Archives</h2>
          <ul>
            <li><a href="index.html#archive-2025-11">November 2025</a></li>
          </ul>
        </section>

        <section class="widget widget_categories">
          <h2 class="widget-title">Categories</h2>
          <ul>
            <li><a href="index.html#category">Uncategorized</a></li>
          </ul>
        </section>

        <section class="widget widget_meta">
          <h2 class="widget-title">Meta</h2>
          <ul>
            <li><a href="index.html#login">Log in</a></li>
            <li><a href="index.html#feed">Entries feed</a></li>
            <li><a href="index.html#comments-feed">Comments feed</a></li>
            <li><a href="https://wordpress.org/">WordPress.org</a></li>
          </ul>
        </section>

      </aside>
    </div>
  </div>
</main>
""" + FOOTER
write(B + "index.html", index)

# ------------------------------------------------------- the remaining pages --
PAGES = [
    ("about-us.html", "About Us",
     "https://mechanic-connectja.mechanic-connect.net/about-us/",
     "an About page for a real company"),
    ("for-customers.html", "For Customers",
     "https://mechanic-connectja.mechanic-connect.net/for-customers/",
     "customer-facing marketing copy"),
    ("for-mechanics.html", "For Mechanics & Garages",
     "https://mechanic-connectja.mechanic-connect.net/for-mechanics/",
     "mechanic- and garage-facing marketing copy"),
    ("faqs.html", "FAQs",
     "https://mechanic-connectja.mechanic-connect.net/mechanic-connect-frequently-asked-questions-faqs/",
     "questions and answers the client never wrote"),
    ("privacy-policy.html", "Privacy Policy",
     "https://mechanic-connectja.mechanic-connect.net/privacy-policy-2/",
     "the text of a privacy policy"),
    ("terms-of-service.html", "Terms of Service",
     "https://mechanic-connectja.mechanic-connect.net/terms-of-service-end-user-agreement-for-mechanic-connect/",
     "the text of a binding end-user agreement"),
]

for filename, heading, url, what in PAGES:
    page = head("%s - Mechanic Connect JA" % heading.replace("&", "&amp;"),
                u"""<meta property="og:site_name" content="Mechanic Connect JA -">
<meta property="og:image" content="assets/cropped-IMG-20250620-WA0006-300x300.jpg">
""", "page") + HEADER + u"""
<main class="site-content">
  <div class="container">
    <article class="entry">

      <!-- ASSUMED: the <h1> below reuses the label this page carries in the
           site navigation. The live page's own title could not be read. -->
      <h1 class="entry-title">%s</h1>

%s
    </article>
  </div>
</main>
""" % (heading.replace("&", "&amp;"), copy_pending(url, what)) + FOOTER
    write(B + filename, page)
