# Go-live runbook

Deploying this repository to Hostinger. Work top to bottom — the order is what
keeps the client's existing site recoverable.

There is a formatted version of this with a checklist you can tick off:
https://claude.ai/code/artifact/15e23e7c-701d-442d-bf1b-29fcd806ca14

| | |
|---|---|
| Repository | `anjuneedham/Mechanic-Connect` |
| Branch | `main` |
| Install path | `public_html` |

Hostinger clones the repository straight into the install path, so `index.php`
has to sit at the top level of the repo. It does. Nothing may be added to the
root that you would not want served publicly.

---

## 1 · Back up, before anything else

This deployment replaces a live WordPress site. The backup is the only way back,
and the only remaining copy of anything not carried across.

- [ ] hPanel → Files → Backups → manual backup. **Wait until it reports complete.**
- [ ] Databases → phpMyAdmin → Export. A file backup alone will not restore WordPress.
- [ ] Confirm the client accepts losing the WordPress admin. Content changes
      become a developer job from here. That is the trade for a site that is fast
      and cannot be broken from a login — but they should agree now, not find out
      in three weeks.

**Everything after this point is reversible only from this backup.**

## 2 · Set the details

Every contact detail, phone number and app link comes from `includes/config.php`.
Nothing else hard-codes them.

- [ ] `LEAD_EMAIL`, `WA_NUMBER`, `SUPPORT_PHONE`, `SUPPORT_HOURS`, `SITE_ADDRESS` —
      confirm each with the client rather than trusting what is there.
- [ ] Leave `IOS_APP` empty. No App Store badge appears anywhere until a live URL
      is confirmed; a badge that leads nowhere is worse than no badge.
- [ ] **If the domain is changing, change all thirteen references:** `SITE_URL` in
      `includes/config.php`, the `Sitemap:` line in `robots.txt`, and eleven
      `<loc>` entries in `sitemap.xml`. Miss one and Google is told the site lives
      somewhere it does not.
- [ ] Commit and push to `main`. Hostinger deploys from `main` only.

A shorter domain is the one improvement no amount of code delivers.
`mechanic-connectja.mechanic-connect.net` cannot be read out on radio, printed on
a flyer, or said in a voice note. Changing it before launch costs nothing.

## 3 · Stage it and get sign-off

The live domain is not where you see the site for the first time. A subfolder
works on every plan — no subdomain needed.

- [ ] hPanel → Advanced → GIT. Repository, branch `main`, install path
      `public_html/staging`.
- [ ] Open `/staging/` and click every page. The site detects the folder it is
      served from and rewrites every link, form action and asset path to match, so
      nothing needs editing between staging and live.
- [ ] Send the link to the client, get sign-off in writing. A staging copy serves
      `noindex, nofollow` automatically and cannot be indexed alongside the real site.

## 4 · Go live

- [ ] Look at the phase 1 backup again and confirm it completed. Do not go on trust.
- [ ] Empty `public_html`. Rather than deleting, move the WordPress files to
      `public_html/old/` — a second safety net that costs nothing.
- [ ] hPanel → Advanced → GIT. Same repository, branch `main`, install path
      `public_html`.
- [ ] Turn on auto-deployment and put the webhook URL into GitHub → Settings →
      Webhooks. After that a push to `main` goes live by itself.

## 5 · Certificate, immediately

The site forces HTTPS from the moment it deploys. Without a certificate that
redirect sends visitors to a browser error rather than the site.

- [ ] hPanel → Security → SSL → install the free certificate.
- [ ] If the site errors before the certificate is ready, comment out lines 14–15
      of `.htaccess` (`RewriteCond %{HTTPS} off` and the line under it), let the
      certificate issue, then put them back. Do not leave them off.

Installing the certificate *before* pointing the domain at the new site avoids
this entirely.

## 6 · Verify

| Open | Expect |
|---|---|
| `/` | Home page, "Stranded? We'll get you moving." |
| `/roadside` | Loads, and the URL keeps no `.php` |
| `/inspection` `/parts` `/services` | All three load |
| `/guide` | Free guide page with the form |
| `/join` `/about` `/faqs` | All three load |
| `/privacy` `/terms` | The client's real legal text, not a placeholder |
| `/assets/The-Roadside-Job-Card.pdf` | A six-page PDF downloads |
| any made-up URL | The site's own 404 page, not Hostinger's |
| `/home/` | Redirects to `/` — old WordPress URLs still work |

- [ ] Every row above. One 404 here is a broken link a customer will find.
- [ ] Submit all three forms — parts, provider, guide. Each lands on the thank-you
      page with matching wording; the guide offers the PDF.
- [ ] Find the test leads: File Manager, **one level above `public_html`** →
      `mc-leads.csv`. Every submission is written here before any email is
      attempted, so a mail failure never loses a lead.
- [ ] Open `https://your-domain/mc-leads.csv`. It must **not** download. It sits
      above the web root, but that file is a list of customers' names and phone
      numbers — confirm it rather than assume it.
- [ ] Check the notification email arrived. Look in spam too.

## 7 · Settle it

- [ ] If notifications went to spam: hPanel → Emails, create an address on the
      domain, switch `submit.php` from `mail()` to authenticated SMTP. The CSV
      keeps working either way, so this is not urgent — but leads nobody reads are
      leads lost.
- [ ] Google Search Console → Sitemaps → `/sitemap.xml`. Old WordPress URLs
      already redirect, so existing rankings carry across.
- [ ] Delete `public_html/staging`.
- [ ] After a clear week, delete `public_html/old`. Keep the hPanel backup.
- [ ] Put a recurring reminder somewhere you actually look to download the leads
      CSV. It grows quietly and nothing prompts you.

---

## If it goes wrong

One recovery path. Do not try to repair a half-deployed site under pressure with
a client watching.

1. hPanel → Files → Backups.
2. Restore the phase 1 backup — files and database both.
3. Confirm the old site loads.
4. Work out what happened on staging, with the pressure off.
