# Mechanic Connect JA — baseline vs v2

Prepared for the YardScale Digital side-by-side. `baseline/` is the current
client site reproduced as-is, faults included. `v2/` is the same brand and the
same copy with the structural faults fixed.

Open `baseline/index.html` first. You will land on a blog roll. That is the
point.

---

## 0 · Read this before the demo: what could not be fetched

Spec §1 requires every page of the live site to be fetched and its copy
extracted verbatim, and the design tokens to be read from the rendered CSS.
**None of that was possible.** This build environment routes all outbound HTTPS
through a policy-enforcing proxy, and

```
mechanic-connectja.mechanic-connect.net
```

is not on its allowlist. The proxy answers `403` to `CONNECT` for that host —
and, as it happens, for every other public host too. The exact refusal:

```
"kind": "connect_rejected",
"detail": "gateway answered 403 to CONNECT (policy denial or upstream failure)",
"host": "mechanic-connectja.mechanic-connect.net:443"
```

So everything in this repository comes from the build spec itself, which
fortunately carries the home page copy, the footer, the head/meta faults and the
full fault register verbatim. Two consequences, both deliberate:

1. **No client copy was invented.** Spec §1 forbids paraphrasing and §8 forbids
   new claims. Where a page's copy could not be read, the page carries its
   correct chrome, meta and structure, and the body is left empty behind a
   `COPY PENDING` marker naming the source URL. Both a marked-up region and a
   short visible note (v2 only) sit there. Nothing plausible-looking has been
   written in its place.
2. **No design token was measured.** Every value in `baseline/tokens.css` is the
   stock Astra default and is marked `/* ASSUMED — Astra default */`, exactly as
   spec §1 instructs for undeterminable values.

Both are recoverable in about an hour from any machine that can reach the site.
See §4 below for the complete list, and §5 for the fastest way to close it.

---

## 1 · The fault register

Every fault from spec §5, reproduced in `baseline/` and fixed in `v2/`.

| # | Fault | `baseline/` behaviour | `v2/` behaviour |
|---|---|---|---|
| 1 | Root serves the blog index, not the home page | `baseline/index.html` **is** the WordPress blog roll: one post, "Mechanic Connect JA!", dated 5 November 2025, with a stock sidebar. The home page is a second click away at `baseline/home.html`. Every direct visit, printed link and ad click lands here. | `v2/index.html` **is** the home page. There is no blog index in v2. Arriving at the root means arriving at the pitch. |
| 2 | WhatsApp link is `http://8764703144` | Reproduced verbatim in the footer of all eight pages. The browser reads it as a host name; the link opens nothing. The primary contact route is dead. | `https://wa.me/18764703144?text=Hi%20Mechanic%20Connect%20JA%2C%20I%20need%20a%20mechanic.` — a real deep link with the message pre-filled, in the footer and beside both hero CTAs. |
| 3 | No App Store or Google Play link anywhere | None. The whole page says "download the app" and offers no way to do it. | Apple App Store and Google Play badges above the fold on the home page, again in the two-path section, and again in the footer of every page. |
| 4 | `Two main call-to-action buttons:` printed in the live copy | Printed on `home.html` exactly as published, above the two links. Build instructions, visible to customers. | Gone. The line was never copy; it was a note to whoever built the page. |
| 5 | CTAs are plain text links, not buttons | Two bare underlined links, unstyled, no padding, no tap target. `baseline/styles.css` deliberately gives them no button styling. | Real buttons: 44px minimum height, AA-contrast fill, visible focus ring, and a clear primary/secondary split. |
| 6 | Two `<h1>` elements on the home page | Both reproduced — the WordPress page title "Mechanic Connect – Home" and the hero heading "Your Trusted Mechanic, On-Demand." | One `<h1>` per page across all seven pages, verified. The hero heading carries it on the home page. |
| 7 | `og:site_name` is `Mechanic Connect JA -` | Reproduced with its trailing hyphen on every page. | `og:site_name` is `Mechanic Connect JA`, plus a real per-page `og:title` and `og:description`. |
| 8 | `og:image` is a cropped 300×300 photo | Reproduced. A square avatar crop where Open Graph expects 1200×630 — every WhatsApp and Instagram share previews badly, and that is where this site's traffic actually is. | `assets/og-image-1200x630.jpg`, declared with `og:image:width`, `og:image:height`, `og:image:alt` and `twitter:card=summary_large_image`. |
| 9 | Contact email is plain text | `Contact: mechanicconnectja@gmail.com` sits in the footer as text. Nothing to tap on a phone. | `<a href="mailto:mechanicconnectja@gmail.com">` in the footer of every page. |
| 10 | Nav exposes Privacy Policy and Terms as top-level items | Reproduced: seven top-level items, two of them legal, given the same weight as the conversion pages. The menu is also emitted twice — desktop and mobile — as the live theme does. | Navigation is five conversion pages: Home, For Customers, For Mechanics & Garages, FAQs, About Us. Privacy Policy and Terms moved to the footer. One menu in the DOM, not two. |
| 11 | No conversion tracking or source parameters | None. An install cannot be traced to the post, flyer or ad that produced it. | `utm_source`, `utm_medium` and `utm_campaign` are read from the query string, held in `sessionStorage` for the session, written into hidden fields on the lead form, and appended to every outbound store link. Nothing else is collected — spec §8. |
| 12 | Long subdomain `mechanic-connectja.mechanic-connect.net` | Out of scope for a static replica; nothing here can register a domain (spec §8). Both builds use relative links, so either drops onto any hostname unchanged. | Same. **This one is a conversation, not a code change** — it needs a short domain the client can say out loud on radio, on a flyer, or in a voice note. |

### The one fault that is not on the list

The current site has no lead capture at all, so the moment a visitor crosses
into the App Store the journey goes dark. `v2/index.html` puts a short form
ahead of the install — name, phone, parish, what they need — because that is the
only point at which an ad click can still be attached to a real person. The
endpoint is configured in exactly one place, `v2/config.js`, alongside the
WhatsApp number and both store URLs.

---

## 2 · Lighthouse, mobile

Lighthouse 13.4.1, default mobile configuration (Moto G Power emulation,
simulated throttling), Chromium 1194, both pages served over HTTP from
`127.0.0.1`. Local serving flatters both numbers equally; treat the gap as the
result, not the absolute values.

| | `baseline/home.html` | `v2/index.html` |
|---|---|---|
| **Performance** | **81** (see below) | **100** |
| **Accessibility** | 100 | 100 |
| **Best practices** | 96 | 100 |
| **SEO** | **82** | **100** |
| First Contentful Paint | 0.9 s | 1.1 s |
| Largest Contentful Paint | 1.1 s | 1.5 s |
| Speed Index | 0.9 s | 1.1 s |
| Total Blocking Time | 0 ms | 0 ms |
| Cumulative Layout Shift | **0.409** | **0.013** |

Spec §6 item 10 set a target of ≥ 90 mobile performance. v2 reaches 100.

### The baseline's performance score is a coin flip

Do not quote a single baseline number without this caveat. Across nine runs of
the identical file on the identical machine:

| Baseline outcome | Runs | Performance | CLS |
|---|---|---|---|
| Layout shift occurs | 6 of 9 | 81 | 0.409 |
| Layout shift does not occur | 3 of 9 | 100 | 0 |

v2 returned 100 with CLS 0.013 in **10 runs out of 10**.

The baseline's split is not measurement noise — it is the fault itself. The
mobile menu is rendered open and then hidden by script, and whether the reader
sees the page jump depends on whether the script wins the race against the first
paint. On a fast device it sometimes does; on a slow one or a poor connection it
mostly does not. The table above reports the common case. The honest summary is
that the baseline scores 81 or 100 depending on the throw, and v2 scores 100
every time.

The other three baseline categories do not depend on timing and were identical
in every run.

**Read the accessibility row carefully.** Both score 100, and that tells you
what automated checks cannot see. Lighthouse does not flag two `<h1>`s on one
page, a navigation menu emitted twice, a contact address that cannot be tapped,
or a WhatsApp link that resolves to nothing. Every one of those is in the
baseline and every one is fixed in v2. Do not let the matching 100 imply the
two pages are equally usable.

Where the baseline loses points:

- **CLS 0.409** — the mobile menu shift described above. This is the theme's own
  behaviour and it has been left alone.
- **SEO 82** — no meta description, and `crawlable-anchors` fails on
  `href="http://8764703144"` and the `href="#"` scroll-to-top control.
- **Best practices 96** — the same dead link.

### A note on how v2's CSS is delivered

Spec §6 item 10 asks for inlined critical CSS and no render-blocking resources.
Both approaches were built and measured, three runs each:

| Approach | Performance | CLS | FCP |
|---|---|---|---|
| Inline critical block + `media="print"` async swap | 84 | 0.314 | 0.9 s |
| Two ordinary `<link rel="stylesheet">` elements | **100** | **0.013** | 1.1 s |

The async swap relaid the page after the first paint, and that shift cost far
more than the 0.2 s of paint it bought back — reproducibly, 3 runs out of 3.
Two same-origin stylesheets under 20 KB total, fetched once and reused by all
seven pages, also beat inlining 20 KB into every page for anyone who visits more
than one. **v2 ships the blocking links**, and this is the one place it departs
from the letter of the spec. `CSS_MODE=async python3 tools/generate-v2.py`
rebuilds the other version if you want to re-run the comparison.

The rest of item 10 is in place: the LCP hero is served through `srcset` at
640 / 960 / 1200 w with `fetchpriority="high"` and declared intrinsic size, and
every below-fold image is `loading="lazy"`.

---

## 3 · What was fixed in `baseline` that should not have been

**Nothing.**

Spec §7 asks for this list and expects it to be empty. It is. Four judgement
calls were made and are recorded here so the claim can be checked rather than
taken on trust — each is a WordPress/Astra default, not an improvement:

1. `alt=""` on the hero image. WordPress emits an empty `alt` when none is set,
   so this is the faithful default rather than a fix. It is also the reason the
   baseline scores 100 on Lighthouse accessibility; see the warning above.
2. `width` and `height` attributes on the hero image. WordPress emits these.
3. `<meta name="viewport">` and `aria-label` on the nav elements. Astra emits
   both.
4. Page links use relative `.html` filenames rather than the live site's pretty
   permalinks (`/about-us/`). This is what makes the replica openable from a
   file system or any static host; it changes no behaviour being demonstrated.

Everything on the fault register survives intact in `baseline/`, including the
ones it would have been trivial and tempting to quietly fix — the dead WhatsApp
link, the printed build instruction, the second `<h1>`, the trailing hyphen.
Each is marked in the source with a comment saying it is deliberate, so nobody
picking the repository up later "tidies" the demo away.

---

## 4 · Could not be determined from the live site

Everything below is blocked on network access to the client's site, not on
effort. Grouped by what it costs to close.

### 4.1 · Design tokens — all assumed

Every value in `baseline/tokens.css` is a stock Astra default and is marked
`/* ASSUMED — Astra default */` in the file. None was measured:

- body font family, weight, size, line-height
- rendered h1–h4 sizes, and their mobile steps
- link colour, text colour, heading colour, page background, border colour
- button background, text colour, radius, padding (recorded for completeness;
  the baseline home CTAs are plain links and consume none of them)
- container max-width and padding
- header background, height, site-title size, nav font size and item spacing
- footer background, text colour, font size and padding

`v2/tokens.css` derives from the same values, so correcting `baseline/tokens.css`
first and then re-deriving is the right order.

### 4.2 · Copy not retrieved

| Where | What is missing |
|---|---|
| `baseline/index.html` | the body of the welcome post "Mechanic Connect JA!" |
| `about-us`, `for-customers`, `for-mechanics`, `faqs`, `privacy-policy`, `terms-of-service` (both builds) | the entire page copy |
| `home.html` / `v2/index.html` | the sentence that follows each bolded lead-in under *How It Works* (3) and *Why Choose Mechanic Connect?* (6). The lead-ins themselves are verbatim from the spec; only the trailing sentences are missing. |

Each gap is marked in the HTML with a `COPY PENDING` comment naming its source
URL.

### 4.3 · Structure assumed rather than observed

- **Blog index sidebar.** The stock WordPress widget set (Search, Recent Posts,
  Recent Comments, Archives, Categories, Meta) is used. The live sidebar could
  not be read. The demo does not depend on which widgets these are — it depends
  on the root URL serving blog chrome at all, which is certain from the spec.
- **Blog index has no `<h1>`.** Standard for a WordPress index, where posts are
  `<h2>`. Not verified.
- **Page titles.** Each interior page's `<h1>` reuses its navigation label. The
  FAQ page's real title is probably longer — its slug is
  `mechanic-connect-frequently-asked-questions-faqs`.
- **`<title>` of the blog index.** Rendered as `Mechanic Connect JA -`, inferred
  from the `og:site_name` fault. Not verified.
- **`og:title` and `og:type`** on baseline pages are WordPress defaults.
- **Footer social separators.** Rendered pipe-separated, matching the footer's
  other link row. The live separator style was not observed.

### 4.4 · Assets not downloaded

All four asset URLs in spec §1 are on the blocked host. Every image in both
builds is a generated grey placeholder, labelled `PLACEHOLDER` in the image
itself so it can never be mistaken for client artwork, and dimensionally exact
so nothing reflows when the real files arrive:

| File | Size | Replaces |
|---|---|---|
| `baseline/assets/cropped-IMG-20250620-WA0006-300x300.jpg` | 300×300 | the hero photo |
| `baseline/assets/cropped-IMG-20250620-WA0006-270x270.jpg` | 270×270 | the tile image |
| `v2/assets/hero-640/960/1200.jpg` | 4:3 | the hero, at three widths |
| `v2/assets/og-image-1200x630.jpg` | 1200×630 | the new share image |
| `*/assets/favicon.ico` | 16/32/48 | the site favicon |

The site logo, wordmark and real favicon could not be retrieved; `v2` uses a
plain `MC` monogram tile in the header in their place.

**The store badges are also stand-ins.** `v2/assets/app-store-badge.svg` and
`google-play-badge.svg` were drawn locally at the official badges' proportions.
Apple's and Google's own artwork is a brand requirement for published apps and
must replace them before this ships. The swap is drop-in; no CSS changes.

### 4.5 · Not supplied, and not invented

No Apple App Store listing was given for the **mechanic staff app** — only the
Google Play package `com.mechanic.mechanics`. So `v2` shows the Play badge alone
on the mechanics path and says nothing about iOS either way. `config.js` has the
slot ready:

```js
mechanic: { ios: null, android: "https://play.google.com/store/apps/details?id=com.mechanic.mechanics" }
```

Fill in `ios` and the badge appears.

---

## 5 · Closing the gaps

In order, from any machine that can reach the client's site:

1. Fetch the eight URLs in spec §1. Paste each page's copy between its
   `COPY PENDING` markers in `baseline/`, verbatim, typos included. Carry the
   same copy into `v2/` — spec §8 rules out rewriting it.
2. Read the computed styles off the live site and replace the assumed values in
   `baseline/tokens.css`. Nothing else hard-codes a colour or size, so that one
   file corrects the whole baseline. Then re-derive `v2/tokens.css`.
3. Download the four assets in spec §1 over the placeholders, keeping the
   filenames. Produce a real 1200×630 share image for v2.
4. Replace the two store badge SVGs with Apple's and Google's official artwork.
5. Set `FORM_ENDPOINT` in `v2/config.js`, and the matching `action` on the form
   in `v2/index.html` (it is the no-JavaScript fallback; the file says so).
6. Add `STORES.mechanic.ios` in `v2/config.js` if an Apple listing exists for
   the staff app.

None of this changes a structural decision. The fault register in §1 stands on
its own.

---

## 6 · Reproducing the Lighthouse numbers

```sh
npm install lighthouse
python3 -m http.server 8080 &
CHROME_PATH=/path/to/chrome npx lighthouse http://127.0.0.1:8080/baseline/home.html --preset=desktop=false
CHROME_PATH=/path/to/chrome npx lighthouse http://127.0.0.1:8080/v2/index.html
```

Lighthouse defaults to the mobile configuration, which is what §2 reports.
