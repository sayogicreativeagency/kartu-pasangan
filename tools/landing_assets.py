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


def stamp_ink():
    size = 512
    a = noise(size, size, 70)
    b = streaks(size, size, 64, 64, 80)
    ink = ImageChops.blend(a, b, .55).point(lambda v: 0 if v < 66 else (255 if v > 86 else (v - 66) * 12))
    out = Image.new('RGBA', (size, size), (0, 0, 0, 255))
    out.putalpha(ink)
    save(out, 'stamp-ink.webp', 80)


if __name__ == '__main__':
    stamp_ink()
