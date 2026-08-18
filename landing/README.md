# landing/ — live lead-magnet page

Published at an Artifact URL (private until shared from the page's share menu).

- `index.html` — the whole page. Self-contained: the 6-page PDF is embedded as
  base64, so there is nothing to host alongside it.
- `assets/The-Roadside-Job-Card.pdf` — the guide as a standalone file.
- `assets/guide-source.html` — the PDF's source. Regenerate with:

  ```sh
  chromium --headless=new --no-pdf-header-footer \
    --print-to-pdf=assets/The-Roadside-Job-Card.pdf assets/guide-source.html
  ```

  Then re-embed it in `index.html` (replace the base64 string in `PDF_B64`).

## How the lead reaches the business

The Artifact sandbox blocks requests to external hosts, so the form cannot POST
to a form service. Instead the form validates locally and then hands the visitor
a `wa.me` link pre-filled with their name, phone, parish, what they need, their
vehicle, and any `utm_source` / `utm_medium` / `utm_campaign` from the URL. The
lead arrives as a WhatsApp message.

That is a real route for this audience, but it depends on the visitor pressing
send. To capture leads server-side, this page needs to move to ordinary hosting
(Hostinger or Netlify) where the form can POST to an endpoint — the markup is
already shaped for it.

The PDF is delivered through the Artifact `downloads` capability, which asks the
viewer to confirm the save. PDFs sit in the extended file-type set, so if a view
cannot save one the page says so and falls back to the full guide, which is on
the page under "Prefer to read it here?".
