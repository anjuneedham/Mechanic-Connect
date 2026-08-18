# Deploying to Netlify

Everything is built and committed. Pick one of the two routes below — both take
about two minutes and neither needs anything typed into a settings form.

> **I could not run this deploy for you.** This build environment has no route
> to `api.netlify.com` (the egress proxy refuses every outbound host) and no
> Netlify credentials, so the last step is yours. Nothing else is outstanding.

---

## Route A — drag and drop (fastest)

1. Download or clone this repository.
2. Go to **https://app.netlify.com/drop**.
3. Drag the **`site/`** folder onto the page.

Live in about thirty seconds on a `*.netlify.app` address. `site/` is committed
already built, so there is nothing to compile.

## Route B — connect the repository (better)

1. Netlify → **Add new site → Import an existing project → GitHub**.
2. Pick `anjuneedham/Mechanic-Connect`, branch `claude/file-as-guide-tj7jsd`.
3. Leave every build setting alone and press deploy.

`netlify.toml` already sets the build command and publish directory. Every push
to the branch redeploys. Merge to `main` first if you want deploys tracking main.

---

## Immediately after the first deploy

**Turn on form notifications, or leads sit in the dashboard unread.**

Netlify → your site → **Forms → Settings → Form notifications → Add
notification → Email notification**. Point it at `mechanicconnectja@gmail.com`.
Do this for both forms.

You will see two forms once the first submission arrives:

| Form | Where it lives | Fields |
|---|---|---|
| `mechanic-request` | the home page, "Tell us what you need" | name, phone, parish, need, utm_source, utm_medium, utm_campaign, landing_page |
| `guide-download` | `/guide`, the free-guide page | name, phone, parish, need, vehicle, and the same campaign fields |

Both are spam-protected with a honeypot field and both land the visitor on
`/thanks.html`, which hands over the PDF and offers a pre-filled WhatsApp
message.

**Set the custom domain** while you are in there: Netlify → Domain management.
This is the moment to leave `mechanic-connectja.mechanic-connect.net` behind —
fault #12 on the register, and the one thing no amount of code could fix.

---

## What is deployed

```
/                     the rebuilt home page
/for-customers        /for-mechanics
/faqs  /about-us  /privacy-policy  /terms-of-service
/guide                the free-guide landing page and lead capture
/thanks               post-submission: the PDF plus a WhatsApp hand-off
/assets/The-Roadside-Job-Card.pdf
/baseline/            the replica of the current site, for the side-by-side
```

`/baseline/` is served with `X-Robots-Tag: noindex, nofollow` and excluded in
`robots.txt`, so it can never compete with the real site in search. Take it out
of `tools/build-site.py` after the pitch if you would rather it not be public
at all.

`netlify/_redirects` also maps the old WordPress permalinks (`/home/`,
`/about-us/`, `/privacy-policy-2/` and the rest) onto the new pages, so anything
already printed or shared keeps working, and adds short paths — `/guide`,
`/customers`, `/mechanics`, `/download`.

---

## Still outstanding before this is a finished site

These are content gaps, not code. Everything structural is done.

1. **Four pages still have no copy** — About Us, FAQs, Privacy Policy, Terms of
   Service. They deploy with a "copy pending" panel. The live client site is
   unreachable from this environment, so the copy could not be fetched. Send it
   and it drops straight in.
   **Privacy Policy and Terms in particular should not go live empty**, and the
   text must be the client's real legal wording, not something drafted here.
2. **Photography.** The hero is a designed brand panel rather than a stock photo
   of a garage that is not the client's. Real photographs replace it directly.
3. **Store badge artwork.** `site/assets/app-store-badge.svg` and
   `google-play-badge.svg` are locally drawn stand-ins at the official
   proportions. Apple's and Google's own artwork is a brand requirement — drop
   the official files in over these two.
4. **The share image.** `assets/og-image-1200x630.jpg` is a placeholder. It is
   what every WhatsApp share of this site will show.

## Rebuilding after an edit

```sh
python3 tools/generate-v2.py     # regenerates the v2/ pages
python3 tools/build-site.py      # assembles site/ for Netlify
python3 tools/build-preview.py   # the single-file preview
```
