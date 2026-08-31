# Mechanic Connect JA — Website

Static PHP site for deployment to Hostinger shared hosting via Git.
Built by YardScale Digital.

## Deploying

1. **Back up first.** hPanel → Files → Backups → create a manual backup and wait for it to
   report complete. The existing WordPress install is destroyed by this deployment.
2. hPanel → Advanced → GIT → add this repository, branch `main`, install path `public_html`.
3. `public_html` must be empty before the first deploy. Clear it *after* the backup confirms.
4. Deploy, then enable auto-deployment and add the webhook URL to the repo so pushes go live.

**Review on a staging subdomain before touching the live domain.** Point a subdomain at a
separate folder, deploy there, and send that link to the client for sign-off.

## Changing content

All contact details, app links and the WhatsApp number live in `includes/config.php`.
Change them there — nothing else hard-codes them.

`IOS_APP` is deliberately empty. Do not add an App Store badge anywhere until a live
App Store URL is confirmed.

## Leads

Both forms post to `submit.php`, which:

1. writes the lead to a CSV **above** the web root (`../mc-leads.csv`), then
2. emails it to the address in `LEAD_EMAIL`.

The CSV is written first, so a mail failure never loses a lead. Download it from
hPanel File Manager, one level above `public_html`.

If Gmail filters the notifications, set up an SMTP sender in hPanel and switch
`submit.php` from `mail()` to authenticated SMTP. The CSV keeps working regardless.

## Campaign links

Append `?src=` to any campaign URL and the value is stored and submitted with the form:

```
https://mechanic-connectja.mechanic-connect.net/roadside?src=tiktok-bio
https://mechanic-connectja.mechanic-connect.net/inspection?src=ad-inspection-01
```

## Claims that must not be added without written sign-off

These are marked with `AMBER` comments in the source. Do not remove the comment
without the approval it names.

| Item | File | Status |
|---|---|---|
| Roadside operating hours | `roadside.php` | Not approved. No hours, no "24/7". |
| Roadside coverage area | `roadside.php` | Not approved. Do not list parishes for roadside. |
| The 15 inspection points | `inspection.php` | Not approved by OH-PEL management. Do not invent. |
| App Store badge / iOS | `services.php` | No live iOS URL supplied. |
| Customer reviews | `services.php` | Empty state. Real attributable reviews only. |
| Privacy policy text | `privacy.php` | Awaiting verbatim text. |
| Terms of service text | `terms.php` | Awaiting verbatim text. |

Approved and published: J$1,500 in-app inspection, J$3,000 outside the app,
15% commission with a J$500 minimum and J$25,000 cap, WhatsApp 876-470-3144.

## Pre-launch checklist

- [ ] Backup taken and confirmed complete
- [ ] Privacy and Terms text supplied and pasted in
- [ ] Both forms submit and arrive (test each once)
- [ ] `mc-leads.csv` is created and is **not** reachable from a browser
- [ ] Old WordPress URLs redirect correctly (see `.htaccess`)
- [ ] Lighthouse mobile ≥ 90 on all four scores
- [ ] Every AMBER item above either resolved with sign-off or still absent

---

## Notes from integration

**The site lives at the repository root.** Hostinger's Git deploy clones the
repo into the install path, so `index.php` must sit at the top level, not in a
`site/` subfolder. Nothing else may be added to the root that you would not want
served from `public_html`.

The earlier static build, the replica of the old WordPress site and the
Lighthouse comparison are not on this branch for that reason. They are preserved
on `claude/file-as-guide-tj7jsd`.

### Two fixes applied to the supplied code

1. **`submit.php` — mail header injection.** The subject line was built from the
   submitted name. `trim()` only strips the ends, so a newline in the middle of a
   name would have let anyone append their own mail headers (`Bcc:`, `Reply-To:`)
   to the notification. The subject is now flattened before use. The body and the
   CSV keep their newlines, which is correct for both.

2. **`includes/config.php` — where the lead CSV lands.** `LEAD_LOG` was
   `dirname($_SERVER['DOCUMENT_ROOT'])`. `DOCUMENT_ROOT` is empty under CLI and
   on some handlers, and `dirname('')` is `.` — which would put a file of
   customers' names and phone numbers inside the web root. It now falls back to
   walking up from the file's own location.

### Verified before pushing

Every page renders with no PHP notice or warning; the 404 handler fires; both
forms record and redirect correctly; the honeypot and the missing-field guard
each reject without writing a row; the spreadsheet formula-injection guard
prefixes `=cmd|calc` correctly; and the CSV is written above the web root.

### Still to confirm

- `IOS_APP` in `includes/config.php` is deliberately empty. No App Store badge
  anywhere until a live URL is confirmed.
- `.htaccess` forces HTTPS unconditionally. Deploy to the staging subdomain
  first and make sure the certificate is issued before pointing the live domain
  at it, or the redirect will loop into an error.
