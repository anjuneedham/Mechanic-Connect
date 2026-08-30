# Putting this on their Hostinger

`hostinger/` is a complete, self-contained copy of the site built for shared
PHP hosting. It is the same markup, styling and assets that deploy to Netlify,
with two things swapped:

| | Netlify | Hostinger |
|---|---|---|
| Lead capture | Netlify Forms records submissions | `submit.php` writes a CSV and emails you |
| Redirects and headers | `_redirects`, `_headers` | `.htaccess` |

Rebuild it any time with `python3 tools/build-hostinger.py`.

---

## Before you upload: one decision

**Their site is WordPress.** Uploading this into `public_html` replaces it.
That is a bigger decision than it looks, so make it deliberately.

**What they lose:** the WordPress admin. Today someone at the client can log in
and edit a page. After this, every content change is a developer job. For a
four-page brochure site that is usually the right trade — it is why the rebuilt
site is fast and why nothing can break it — but the client has to agree to it,
not discover it.

**What they gain:** the routing fault disappears, the site loads in a fraction
of the time, there is no WordPress or plugin to keep patched, and nothing can be
defaced through a stale login.

### The safe order of operations

1. **Back up the WordPress site first.** hPanel → Files → Backups, and take a
   database export too. Do not skip this: it is the only copy of the client's
   page copy, which is still missing from four pages here.
2. **Upload to a subfolder and test.** Put the contents of `hostinger/` into
   `public_html/new/` and open `.../new/`. Everything works there — the forms,
   the redirects, the PDF — because every path in the site is relative.
3. **Pull the copy off the old site while it is still up.** About Us, FAQs,
   Privacy Policy and Terms. This is the last convenient moment.
4. **Swap.** Move the WordPress files into `public_html/old/`, move the contents
   of `new/` up to `public_html/`, and check the site loads.
5. **Delete `old/` only after a week of the new site behaving.**

If they would rather keep WordPress for a blog, leave it at `public_html/blog/`
and put the static site at the root. The two coexist happily.

---

## Uploading

**hPanel File Manager** — Files → File Manager → `public_html`, then Upload.
Upload `mechanic-connect-hostinger.zip` and use *Extract* rather than dragging
hundreds of files in one at a time.

**Or FTP** — hPanel → Files → FTP Accounts for the credentials, then drop the
*contents* of `hostinger/` into `public_html`.

> Upload the **contents** of the folder, not the folder itself. `index.html`
> must sit directly in `public_html`, not in `public_html/hostinger/`.

Hidden files matter: **`.htaccess` and `_leads/.htaccess` must both arrive.**
Some FTP clients skip dotfiles by default — turn on "show hidden files" before
you start, and check afterwards.

---

## Immediately after uploading

### 1. Set the notification address

Open `submit.php` and change the first setting:

```php
const NOTIFY_TO = 'mechanicconnectja@gmail.com';
```

### 2. Send yourself a test lead

Fill in the form on the live site. You should land on the thank-you page and
get an email. **Then check the CSV exists** at `_leads/leads.csv` through the
File Manager.

### 3. Prove the lead file is not public

Open `https://your-domain/_leads/leads.csv` in a browser. **You must get a 404
or 403.** If the file downloads, `.htaccess` did not upload — fix that before
sending any traffic, because that file is a list of customers' names and phone
numbers.

### 4. Turn on HTTPS

hPanel → Security → SSL, install the free certificate. Once it is active,
uncomment the two `RewriteCond`/`RewriteRule` HTTPS lines near the top of
`.htaccess`. Doing it before the certificate exists redirects the site into an
error.

---

## How lead capture works here

`submit.php` does five things, in order:

1. Drops anything that filled the honeypot field or submitted faster than a
   person can type.
2. Requires name, phone, parish and need; checks the phone has 7–15 digits.
3. Strips carriage returns from every field, so nothing can forge a mail header.
4. Appends the lead to `_leads/leads.csv` with a file lock.
5. Emails a notification and redirects to `thanks.html`.

**The CSV is the record; the email is only the notification.** Mail sent by PHP
from shared hosting is classified as spam often enough that it cannot be the
only copy. Download the CSV periodically, or better, set up a real mailbox on
the domain (hPanel → Emails) and switch `submit.php` to SMTP — that is a small
change and it is worth doing once the site is settled.

A rejected submission redirects to `thanks.html?s=…` and the page shows what
went wrong instead of thanking someone whose details were not saved.

---

## What is not carried over from the Netlify build

- **`/baseline/`** — the replica of the current site is a pitch asset, not part
  of the client's live site, so it is left out of this build entirely.
- **The four pages still waiting on copy.** Same as Netlify: written, sitting in
  `v2/`, not deployed. See `DEPLOY.md` for how to put one live.
- **Privacy Policy.** Both forms take a name and a phone number, and this build
  now stores them in a file on the client's own server. That makes a published
  privacy policy more pressing here than it was on Netlify, not less.
