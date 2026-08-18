# -*- coding: utf-8 -*-
"""Assembles site/ — the directory Netlify publishes.

    site/            the rebuilt Mechanic Connect JA site (from v2/)
    site/baseline/   the replica of the client's current site, for the
                     side-by-side. Served with X-Robots-Tag: noindex so it
                     never competes with the real site in search.

Run from the repository root:  python3 tools/build-site.py

The output is committed, so the site can also be deployed by dragging the
site/ folder onto app.netlify.com/drop with no build step at all. netlify.toml
runs this script on git-connected deploys so the two never drift.
"""
import os
import shutil

# Only these pages are deployed. The rest are generated into v2/ and kept in
# the repository, waiting on the client's copy — see LIVE_PAGES at the top of
# tools/generate-v2.py, which must list the same set.
LIVE_PAGES = ("index.html", "guide.html", "thanks.html")

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
OUT = os.path.join(ROOT, "site")


def copy_tree(src, dst):
    if os.path.isdir(dst):
        shutil.rmtree(dst)
    shutil.copytree(src, dst)
    return sum(len(f) for _, _, f in os.walk(dst))


if os.path.isdir(OUT):
    shutil.rmtree(OUT)

n_site = copy_tree(os.path.join(ROOT, "v2"), OUT)

# Drop the pages that are not live yet, so nothing half-finished is reachable
# by guessing a URL.
for name in sorted(os.listdir(OUT)):
    if name.endswith(".html") and name not in LIVE_PAGES:
        os.remove(os.path.join(OUT, name))
        n_site -= 1
n_base = copy_tree(os.path.join(ROOT, "baseline"), os.path.join(OUT, "baseline"))

# Netlify reads _headers and _redirects from the published directory, and
# robots.txt is served from the site root.
shutil.copy(os.path.join(ROOT, "netlify", "_headers"), os.path.join(OUT, "_headers"))
shutil.copy(os.path.join(ROOT, "netlify", "_redirects"), os.path.join(OUT, "_redirects"))
shutil.copy(os.path.join(ROOT, "netlify", "robots.txt"), os.path.join(OUT, "robots.txt"))

print("site/          %2d files  (the rebuilt site)" % n_site)
print("site/baseline/ %2d files  (replica, noindex)" % n_base)
print("built at", OUT)
