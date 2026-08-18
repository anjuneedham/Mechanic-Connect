from PIL import Image, ImageDraw, ImageFont
import os

def font(sz):
    for p in ("/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf",
              "/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf"):
        if os.path.exists(p):
            return ImageFont.truetype(p, sz)
    return ImageFont.load_default()

def make(path, w, h, lines, bg=(214, 216, 219), fg=(90, 94, 100), fmt=None):
    img = Image.new("RGB", (w, h), bg)
    d = ImageDraw.Draw(img)
    d.rectangle([0, 0, w - 1, h - 1], outline=(168, 172, 178), width=2)
    d.line([(0, 0), (w, h)], fill=(198, 201, 206), width=1)
    d.line([(0, h), (w, 0)], fill=(198, 201, 206), width=1)
    sz = max(9, int(min(w, h) / 16))
    f = font(sz)
    total = len(lines) * (sz + 6)
    y = (h - total) // 2
    for ln in lines:
        bb = d.textbbox((0, 0), ln, font=f)
        d.rectangle([(w - (bb[2] - bb[0])) // 2 - 6, y - 3,
                     (w + (bb[2] - bb[0])) // 2 + 6, y + sz + 5], fill=bg)
        d.text(((w - (bb[2] - bb[0])) // 2, y), ln, font=f, fill=fg)
        y += sz + 6
    os.makedirs(os.path.dirname(path), exist_ok=True)
    img.save(path, format=fmt, quality=82)
    print(path, img.size)

B = "baseline/assets/"
V = "v2/assets/"
ph = ["PLACEHOLDER", "original not", "downloadable"]
make(B + "cropped-IMG-20250620-WA0006-300x300.jpg", 300, 300, ph + ["300 x 300"])
make(B + "cropped-IMG-20250620-WA0006-270x270.jpg", 270, 270, ph + ["270 x 270"])
make(V + "hero-1200x900.jpg", 1200, 900, ["PLACEHOLDER", "hero image", "1200 x 900"])
make(V + "og-image-1200x630.jpg", 1200, 630, ["PLACEHOLDER", "Open Graph image", "1200 x 630"])

ico = Image.new("RGB", (64, 64), (1, 112, 185))
di = ImageDraw.Draw(ico)
fi = font(34)
bb = di.textbbox((0, 0), "MC", font=fi)
di.text(((64 - (bb[2] - bb[0])) // 2, (64 - (bb[3] - bb[1])) // 2 - bb[1]), "MC", font=fi, fill=(255, 255, 255))
for p in (B + "favicon.ico", V + "favicon.ico"):
    ico.save(p, format="ICO", sizes=[(16, 16), (32, 32), (48, 48)])
    print(p)
