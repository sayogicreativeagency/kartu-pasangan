"""Generator tekstur tinta landing (public/landing). Murni prosedural, hanya butuh Pillow.

Jalankan dari root repo: python tools/landing_assets.py
"""
import math
import random
from pathlib import Path

from PIL import Image, ImageChops, ImageDraw, ImageFilter

OUT = Path(__file__).resolve().parent.parent / 'public' / 'landing'
OUT.mkdir(parents=True, exist_ok=True)
random.seed(18)


def noise(w, h, sigma=40):
    return Image.effect_noise((w, h), sigma)


def streaks(w, h, cells_x, cells_y, sigma=60):
    return noise(cells_x, cells_y, sigma).resize((w, h), Image.BICUBIC)


def save(img, name, quality=86):
    img.save(OUT / name, 'WEBP', quality=quality, method=6)
    print(name, img.size, round((OUT / name).stat().st_size / 1024), 'KB')


# ---------- cap lipstik ----------
def smooth(points, steps=14):
    """Catmull-Rom tertutup: kontur bibir tanpa sudut poligon."""
    out = []
    n = len(points)
    for i in range(n):
        p0, p1, p2, p3 = points[i - 1], points[i], points[(i + 1) % n], points[(i + 2) % n]
        for k in range(steps):
            u = k / steps
            out.append(tuple(
                .5 * (2 * p1[j] + (p2[j] - p0[j]) * u + (2 * p0[j] - 5 * p1[j] + 4 * p2[j] - p3[j]) * u * u
                      + (3 * p1[j] - p0[j] - 3 * p2[j] + p3[j]) * u ** 3)
                for j in (0, 1)))
    return out


def lips():
    ss = 2
    W, H = 900, 560
    img = Image.new('L', (W * ss, H * ss), 0)
    d = ImageDraw.Draw(img)
    s = 7.2 * ss
    place = lambda pts: [(40 * ss + x * s, 22 * ss + y * s) for x, y in pts]
    upper = [(3, 37), (15, 27), (28, 15), (40, 7), (50, 5), (56, 9), (60, 13), (65, 8), (72, 4), (84, 7), (97, 17), (109, 28), (117, 36), (100, 34), (80, 33), (60, 36), (42, 33), (20, 34)]
    lower = [(6, 41), (22, 43), (42, 46), (60, 47), (80, 45), (98, 43), (114, 40), (106, 53), (92, 64), (76, 71), (60, 73), (44, 71), (27, 63), (13, 53)]
    d.polygon(place(smooth(upper)), fill=255)
    d.polygon(place(smooth(lower)), fill=255)

    # kerut menjalar dari garis mulut ke tepi bibir, melengkung mengikuti bentuk
    for top, count in ((True, 34), (False, 40)):
        for i in range(count):
            f = (i + random.uniform(.15, .85)) / count
            x = 12 + f * 96
            spread = (x - 60) / 60
            y0 = (35 if top else 44) + random.uniform(-1, 1)
            reach = random.uniform(.45, .9) * ((26 if top else 28) * (1 - abs(spread) ** 2.2) + 2)
            pts = []
            for k in range(7):
                u = k / 6
                dy = -reach * u if top else reach * u
                pts.append((x + spread * reach * .42 * u + math.sin(u * 3 + i) * .35, y0 + dy))
            d.line(place(pts), fill=0, width=random.choice((2, 3, 3, 4, 5)) * ss)

    img = img.filter(ImageFilter.GaussianBlur(1.6 * ss)).resize((W, H), Image.LANCZOS)
    # tepi tinta sedikit makan kertas, lalu alpha dibuat biner
    tooth = noise(W, H, 34).filter(ImageFilter.GaussianBlur(.8))
    mixed = ImageChops.add(img, tooth.point(lambda v: int((v - 128) * .55) + 64), scale=1, offset=-64)
    alpha = mixed.point(lambda v: 255 if v > 128 else 0)

    out = Image.new('RGBA', (W, H), (0, 0, 0, 255))
    out.putalpha(alpha)
    save(out, 'lips.webp', 90)


def stamp_ink():
    size = 512
    a = noise(size, size, 70)
    b = streaks(size, size, 64, 64, 80)
    ink = ImageChops.blend(a, b, .55).point(lambda v: 0 if v < 66 else (255 if v > 86 else (v - 66) * 12))
    out = Image.new('RGBA', (size, size), (0, 0, 0, 255))
    out.putalpha(ink)
    save(out, 'stamp-ink.webp', 80)


if __name__ == '__main__':
    lips()
    stamp_ink()
