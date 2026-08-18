# tools/

Development helpers. **None of these are part of the site.** `baseline/` and
`v2/` are plain HTML and CSS: open a file, or drop the directory on any static
host, and it works. Nothing is compiled, bundled or transformed at serve time
(spec §2).

These scripts exist for one reason: eight baseline pages and seven v2 pages
share a header and footer, and hand-copying that chrome fifteen times is how it
drifts. Each script prints the same static HTML the repository already contains.

| Script | What it writes |
|---|---|
| `generate-baseline.py` | the eight files in `baseline/` |
| `generate-v2.py` | the seven files in `v2/` |
| `generate-placeholder-assets.py` | the placeholder images in both `assets/` directories |

Run them from the repository root:

```sh
python3 tools/generate-baseline.py
python3 tools/generate-v2.py
```

`generate-v2.py` honours `CSS_MODE=async|blocking` (default `blocking`) — the
two stylesheet-delivery strategies that were measured head to head. See the
performance section of `COMPARISON.md`.

Editing the HTML by hand is fine. If you do, keep the scripts in step or delete
them; a generator that no longer matches its output is worse than none.
