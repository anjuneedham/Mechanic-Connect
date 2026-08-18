# Mechanic Connect JA — site replica and rebuild

Two versions of the same site, built for a side-by-side demo.

| | What it is |
|---|---|
| **`baseline/`** | The client's current WordPress/Astra site reproduced faithfully, **faults included**. Nothing here is improved. |
| **`v2/`** | The same brand and the same copy, with every structural fault fixed. |

**Start at `baseline/index.html`.** You will land on a blog roll rather than the
home page. That is not a mistake in the replica — it is the client's live
routing, and it is the centrepiece of the demo. The home page is at
`baseline/home.html`.

Then open `v2/index.html`, which *is* the home page.

## Read next

**[`COMPARISON.md`](COMPARISON.md)** — the fault-by-fault table, the Lighthouse
mobile scores for both builds, and a full account of what could not be fetched
from the live site and had to be assumed. Read §0 of it before demoing.

## Running it

Plain HTML and CSS. No framework, no build step, no bundler. Open the files
directly, or serve the directory:

```sh
python3 -m http.server 8080
```

Both builds work with JavaScript disabled.

## Layout

```
baseline/   index.html (blog index)  home.html  about-us.html  for-customers.html
            for-mechanics.html  faqs.html  privacy-policy.html  terms-of-service.html
            styles.css  tokens.css  nav.js  assets/

v2/         index.html (home page)  for-customers.html  for-mechanics.html
            about-us.html  faqs.html  privacy-policy.html  terms-of-service.html
            styles.css  tokens.css  config.js  app.js  assets/

tools/      page generators — development helpers, not part of the site
```

Everything configurable in `v2` — the lead form endpoint, the WhatsApp number,
both apps' store URLs — lives in `v2/config.js`.

## Status

Structure, meta, navigation, styling and behaviour are complete in both builds.
The client's page copy and the real design tokens could not be fetched (the live
host is blocked by this build environment's network policy), so those gaps are
marked `COPY PENDING` in the HTML and `ASSUMED` in `baseline/tokens.css`.
`COMPARISON.md` §4 lists every one of them and §5 says how to close them.
