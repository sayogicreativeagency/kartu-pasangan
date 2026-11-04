"""Generate gambar brand lewat OpenAI. Key dan model dibaca dari .env, hasil ditulis ke tools/generated.

Jalankan dari root repo: python tools/generate_images.py [logo door og]
"""
import base64
import json
import re
import sys
import urllib.error
import urllib.request
from concurrent.futures import ThreadPoolExecutor
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
OUT = ROOT / 'tools' / 'generated'
OUT.mkdir(exist_ok=True)
ENV = (ROOT / '.env').read_text(encoding='utf-8')


def env(name):
    return re.search(rf'^{name}=(.*)$', ENV, re.M).group(1).strip().strip('"')


INKS = 'Flat screen-print look: only flat solid inks, hot pink #ff5a9e, deep aubergine #3a1238, blush #ffe3ee. No gradients, no 3D, no bevels, no glow, no soft shadows.'

JOBS = {
    'logo': dict(size='1024x1024', background='transparent', prompt=(
        'A minimal logo mark, centered with generous empty margin, on a fully transparent background. '
        'A hotel door hanger silhouette: rounded head with a circular hole, narrow neck, wider rectangular body with soft corners, in solid hot pink #ff5a9e. '
        'On the body, a bold lipstick kiss print in deep aubergine #3a1238. '
        'Thick confident simple shapes that stay readable at 32 pixels. No text, no letters. ' + INKS)),
    'door': dict(size='1024x1536', background='transparent', prompt=(
        'A front-on flat illustration of one wide closed hotel room door, the door frame touching all four edges of the canvas, on a fully transparent background. '
        'The door is wide, its proportions exactly the canvas ratio of two wide by three tall. '
        'Line art only, in hot pink #ff5a9e ink with one consistent thick line weight, nothing filled in, everything between the lines fully transparent. '
        'Shows: door frame with moulded architrave, one large upper recessed panel and one large lower recessed panel, each with one inner moulding line, two simple hinges on the left edge. '
        'No door knob, no handle, no keyhole. Strictly symmetric and orthographic, no perspective, no floor, no text, no numbers. ' + INKS)),
    'lips': dict(size='1536x1024', background='transparent', prompt=(
        'A single bold lipstick kiss print filling most of the canvas, on a fully transparent background. Real lipstick print texture: lip creases radiating from the mouth line, slightly broken inked edges. '
        'One solid ink only, pure flat black, every pixel either solid black or fully transparent, no grey, no gradient, no shadow, no paper, no text.')),
}


def generate(name):
    job = JOBS[name]
    body = dict(model=env('AI_API_MODEL_GAMBAR'), prompt=job['prompt'], size=job['size'], background=job['background'],
                quality='high', output_format='png', n=1)
    req = urllib.request.Request('https://api.openai.com/v1/images/generations', json.dumps(body).encode(),
                                 {'Authorization': 'Bearer ' + env('OPENAI_API_KEY'), 'Content-Type': 'application/json'})
    try:
        with urllib.request.urlopen(req, timeout=280) as res:
            data = json.load(res)
    except urllib.error.HTTPError as e:
        err = json.loads(e.read()).get('error', {})
        return f"{name}: GAGAL {e.code} {err.get('code')} {str(err.get('message'))[:160]}"
    (OUT / f'{name}.png').write_bytes(base64.b64decode(data['data'][0]['b64_json']))
    (OUT / f'{name}.prompt.txt').write_text(f"{body['model']}\n{job['prompt']}\n", encoding='utf-8')
    return f"{name}: ok {(OUT / f'{name}.png').stat().st_size // 1024} KB, token {data.get('usage', {}).get('total_tokens')}"


if __name__ == '__main__':
    names = sys.argv[1:] or list(JOBS)
    with ThreadPoolExecutor(3) as pool:
        for line in pool.map(generate, names):
            print(line)
