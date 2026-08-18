# -*- coding: utf-8 -*-
"""Flattens the rebuilt site into ONE self-contained HTML file.

Why: the published-artifact host serves a single file with no relative fetches
and no requests to other origins, so the multi-file site cannot run there as-is.
This inlines the stylesheets, the scripts and every image, rewrites the
cross-page links to in-page anchors, and swaps the Netlify form submission for
a WhatsApp hand-off (an artifact cannot POST to Netlify).

It is a preview for showing the site before it is deployed. The real site is
site/, deployed to Netlify — that is where the form actually captures leads.

    python3 tools/build-preview.py
"""
import base64
import io
import mimetypes
import os
import re

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SRC = os.path.join(ROOT, "v2")
OUT = os.path.join(ROOT, "preview", "mechanic-connect-preview.html")

GUIDE_URL = "https://claude.ai/code/artifact/e93039e7-b0db-48c2-acbd-76f67563c6ba"


def read(name):
    return io.open(os.path.join(SRC, name), encoding="utf-8").read()


def data_uri(rel):
    path = os.path.join(SRC, rel)
    mime = mimetypes.guess_type(path)[0] or "application/octet-stream"
    if rel.endswith(".svg"):
        mime = "image/svg+xml"
    with open(path, "rb") as fh:
        return "data:%s;base64,%s" % (mime, base64.b64encode(fh.read()).decode())


html = read("index.html")

# ---- inline the stylesheets -------------------------------------------------
css = read("tokens.css") + "\n" + read("styles.css")
html = re.sub(
    r'<link rel="stylesheet" href="tokens\.css">\s*<link rel="stylesheet" href="styles\.css">',
    "<style>\n%s\n</style>" % css,
    html,
)

# ---- inline the scripts -----------------------------------------------------
html = html.replace('<script src="config.js" defer></script>',
                    "<script>\n%s\n</script>" % read("config.js"))
html = html.replace('<script src="app.js" defer></script>',
                    "<script>\n%s\n</script>" % read("app.js"))

# ---- inline every image -----------------------------------------------------
for rel in sorted(os.listdir(os.path.join(SRC, "assets"))):
    if rel.endswith((".jpg", ".png", ".svg", ".ico")):
        html = html.replace('"assets/%s"' % rel, '"%s"' % data_uri("assets/" + rel))
        html = re.sub(r'assets/%s(\s+\d+w)' % re.escape(rel),
                      lambda m: data_uri("assets/" + rel) + m.group(1), html)

# ---- rewrite cross-page links ----------------------------------------------
# The two audience pages are already summarised by the cards in #get-the-app;
# the four pages still waiting on the client's copy are dropped from the
# preview's navigation rather than shown empty.
for href, anchor in (("for-customers.html", "#get-the-app"),
                     ("for-mechanics.html", "#get-the-app"),
                     ("index.html", "#top"),
                     ("thanks.html", "#get-started")):
    html = html.replace('href="%s"' % href, 'href="%s"' % anchor)
    html = html.replace('href="%s#' % href, 'href="#')

html = html.replace('href="guide.html"', 'href="%s"' % GUIDE_URL)

for dead in ("faqs.html", "about-us.html", "privacy-policy.html", "terms-of-service.html"):
    html = re.sub(r'\s*<li><a href="%s"[^>]*>[^<]*</a></li>' % re.escape(dead), "", html)
    html = re.sub(r'<a href="%s"[^>]*>([^<]*)</a>' % re.escape(dead), r"\1", html)

html = html.replace('<body>', '<body id="top">', 1)

# ---- the form: WhatsApp hand-off instead of a Netlify POST ------------------
html = html.replace('action="/thanks.html"', 'action="#get-started"')
html += u"""
<script>
/* PREVIEW ONLY. The deployed site posts this form to Netlify Forms, which
   records the lead. A published artifact cannot post to another origin, so
   here the same details are handed to WhatsApp instead — the lead still
   reaches the business, it just needs the visitor to press send. */
(function () {
  var form = document.querySelector("form[data-lead-form]");
  if (!form) { return; }
  var clone = form.cloneNode(true);
  form.parentNode.replaceChild(clone, form);

  clone.addEventListener("submit", function (event) {
    event.preventDefault();
    if (!clone.reportValidity()) { return; }

    var data = new FormData(clone);
    var lines = ["Hi Mechanic Connect JA - I filled in the form on your site."];
    [["name", "Name"], ["phone", "Phone"], ["parish", "Parish"], ["need", "I need"],
     ["utm_source", "utm_source"], ["utm_medium", "utm_medium"],
     ["utm_campaign", "utm_campaign"]].forEach(function (pair) {
      var value = data.get(pair[0]);
      if (value) { lines.push(pair[1] + ": " + value); }
    });

    var note = document.createElement("p");
    note.className = "form-legal";
    note.style.marginTop = "1rem";
    note.innerHTML = '<a class="btn btn--primary" style="width:100%" href="'
      + "https://wa.me/18764703144?text=" + encodeURIComponent(lines.join("\\n"))
      + '">Send these details on WhatsApp</a>';
    clone.appendChild(note);
    note.querySelector("a").focus();
  });
})();
</script>
"""

os.makedirs(os.path.dirname(OUT), exist_ok=True)
io.open(OUT, "w", encoding="utf-8").write(html)
print("wrote", OUT, round(os.path.getsize(OUT) / 1024.0, 1), "KB")
left = re.findall(r'(?:href|src)="(?!https?:|#|mailto:|data:)([^"]+)"', html)
print("unresolved relative references:", sorted(set(left)) or "none")
