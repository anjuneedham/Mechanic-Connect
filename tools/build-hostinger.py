# -*- coding: utf-8 -*-
"""Assembles hostinger/ — the folder that gets uploaded to Hostinger.

Hostinger is shared PHP hosting, not Netlify, so two things have to change:

  1. Lead capture. Netlify records submissions itself; Hostinger does not.
     The forms are repointed at submit.php, which writes every lead to a CSV
     outside the web root and emails a notification.
  2. Redirects and headers. Netlify reads _redirects and _headers; Apache and
     LiteSpeed read .htaccess. The Netlify files are dropped and .htaccess
     goes in instead.

Everything else — the markup, the stylesheets, the assets — is identical to
what deploys to Netlify.

    python3 tools/build-hostinger.py
"""
import io
import os
import re
import shutil

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SRC = os.path.join(ROOT, "site")
CFG = os.path.join(ROOT, "hostinger-src")
OUT = os.path.join(ROOT, "hostinger")

# The Netlify replica is a pitch asset, not part of the client's live site.
DROP = ("_headers", "_redirects", "baseline")


NETLIFY_NOTE = re.compile(
    r"<!--\s*Netlify Forms\..*?-->", re.S)

PHP_NOTE = """<!-- Lead capture on shared hosting. The form posts to submit.php, which
         validates it, appends it to _leads/leads.csv (blocked from the web by
         its own .htaccess) and emails a notification. The CSV is the record;
         mail from shared hosting is unreliable enough that it cannot be the
         only copy.

         The honeypot below is hidden from people and filled by bots, and the
         "started" timestamp rejects anything submitted faster than a person
         can type. Set NOTIFY_TO at the top of submit.php. -->"""


def rewire(html):
    """Point the forms at submit.php and strip the Netlify-only attributes."""
    html = NETLIFY_NOTE.sub(PHP_NOTE, html)
    html = html.replace(' data-netlify="true" netlify-honeypot="bot-field"', "")
    html = html.replace('action="/thanks.html"', 'action="submit.php"')
    html = html.replace('href="/', 'href="')          # Netlify root-relative
    # A timestamp the handler uses to reject submissions filled faster than a
    # person can type. Added next to the honeypot that is already in the markup.
    html = html.replace(
        '<input type="hidden" name="form-name"',
        '<input type="hidden" name="started" value="__TS__">\n      '
        '<input type="hidden" name="form-name"',
    )
    return html


if os.path.isdir(OUT):
    shutil.rmtree(OUT)
shutil.copytree(SRC, OUT)

for name in DROP:
    path = os.path.join(OUT, name)
    if os.path.isdir(path):
        shutil.rmtree(path)
    elif os.path.exists(path):
        os.remove(path)

pages = []
for name in sorted(os.listdir(OUT)):
    if not name.endswith(".html"):
        continue
    path = os.path.join(OUT, name)
    html = io.open(path, encoding="utf-8").read()
    io.open(path, "w", encoding="utf-8").write(rewire(html))
    pages.append(name)

# The fetch() path in app.js and in the guide page's own script must post to
# the same place the no-JavaScript path does.
for name in ("app.js", "config.js", "guide.html"):
    path = os.path.join(OUT, name)
    if not os.path.exists(path):
        continue
    text = io.open(path, encoding="utf-8").read()
    text = text.replace('FORM_POST_PATH: "/"', 'FORM_POST_PATH: "submit.php"')
    text = text.replace('fetch("/", {', 'fetch("submit.php", {')
    text = text.replace('cfg.FORM_POST_PATH || "/"', 'cfg.FORM_POST_PATH || "submit.php"')
    text = text.replace('THANKS_PAGE: "/thanks.html"', 'THANKS_PAGE: "thanks.html"')
    # The comments in these files describe Netlify. On this build they would be
    # wrong, and a wrong comment is worse than none.
    text = text.replace(
        "Lead capture runs on Netlify Forms, so there is no endpoint to configure:\n"
        "     Netlify finds the forms in the deployed HTML and records submissions\n"
        "     itself. What you may want to change:",
        "Lead capture runs through submit.php in this same folder. What you may\n"
        "     want to change:")
    text = text.replace(
        "       FORM_POST_PATH  where the browser posts. \"/\" is Netlify's convention and\n"
        "                       works from any page; the form-name field is what routes\n"
        "                       the submission.",
        "       FORM_POST_PATH  the handler the browser posts to.")
    text = text.replace(
        "     Submissions appear in the Netlify dashboard under Forms. Set up email or\n"
        "     webhook notifications there — Forms > Settings > Form notifications. */",
        "     Leads are appended to _leads/leads.csv and emailed to the address set as\n"
        "     NOTIFY_TO at the top of submit.php. The CSV is the record; the email is\n"
        "     only the notification. */")
    text = text.replace(
        "  /* Netlify records the submission either way. With JavaScript off the browser\n"
        "     posts natively and Netlify redirects; the code below only removes the page\n"
        "     reload from the middle of that, and stashes the visitor's details so the\n"
        "     thank-you page can greet them and pre-fill their WhatsApp message. If the\n"
        "     fetch fails for any reason we fall back to a real browser submit rather\n"
        "     than lose the lead. */",
        "  /* submit.php records the lead either way. With JavaScript off the browser\n"
        "     posts natively and PHP redirects; the code below only removes the page\n"
        "     reload from the middle of that, and stashes the visitor's details so the\n"
        "     thank-you page can greet them and pre-fill their WhatsApp message. If the\n"
        "     fetch fails for any reason we fall back to a real browser submit rather\n"
        "     than lose the lead. */")
    text = text.replace(
        "        the lead form without a page reload (Netlify Forms records it\n"
        "        either way — see initForm)",
        "        the lead form without a page reload (submit.php records it either\n"
        "        way — see initForm)")
    io.open(path, "w", encoding="utf-8").write(text)

# The server-side pieces.
shutil.copy(os.path.join(CFG, "submit.php"), os.path.join(OUT, "submit.php"))
shutil.copy(os.path.join(CFG, "htaccess"), os.path.join(OUT, ".htaccess"))
shutil.copy(os.path.join(CFG, "robots.txt"), os.path.join(OUT, "robots.txt"))

leads = os.path.join(OUT, "_leads")
os.makedirs(leads, exist_ok=True)
shutil.copy(os.path.join(CFG, "leads-htaccess"), os.path.join(leads, ".htaccess"))
io.open(os.path.join(leads, "index.html"), "w", encoding="utf-8").write("")

# Fill the anti-bot timestamp at build time. It only has to be older than the
# page view, which it always is.
import time
stamp = str(int(time.time()))
for name in pages:
    path = os.path.join(OUT, name)
    text = io.open(path, encoding="utf-8").read().replace("__TS__", stamp)
    io.open(path, "w", encoding="utf-8").write(text)

print("hostinger/ built —", ", ".join(pages))
print("  submit.php, .htaccess, robots.txt, _leads/ (protected)")
print("  upload the CONTENTS of hostinger/ into public_html")
