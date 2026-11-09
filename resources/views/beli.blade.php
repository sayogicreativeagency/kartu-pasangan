@php($rp = fn (int $n) => 'Rp '.number_format($n, 0, ',', '.'))
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Buka akses · Sayogi Mesra</title>
<meta name="robots" content="noindex">
<link rel="icon" href="/favicon.ico" sizes="any">
<meta name="theme-color" content="#170f16">
<style>
  :root{--bg:#170f16;--line:#3d2639;--ink:#fdeef5;--dim:#c39fb6;--rose:#ff7aa2;--rose2:#ff4d84}
  *{box-sizing:border-box}
  body{margin:0;min-height:100dvh;display:flex;align-items:center;justify-content:center;padding:24px 16px;
    background:var(--bg);color:var(--ink);font-family:system-ui,-apple-system,"Segoe UI",Roboto,sans-serif}
  main{width:100%;max-width:400px}
  h1{font-size:22px;margin:0 0 4px}
  p{color:var(--dim);font-size:14px;line-height:1.5;margin:0 0 14px}
  .harga{font-size:30px;font-weight:800;color:var(--ink);margin:4px 0 2px;font-variant-numeric:tabular-nums}
  .qr{display:block;width:240px;height:240px;margin:16px auto;background:#fff;border-radius:12px;padding:8px}
  label{display:block;font-size:13px;color:var(--dim);margin:14px 0 6px}
  input{width:100%;background:#1d1220;border:1px solid var(--line);color:var(--ink);border-radius:12px;padding:13px;font-size:16px}
  input:focus{outline:2px solid var(--rose);outline-offset:1px}
  button{width:100%;margin-top:16px;padding:15px;border:0;border-radius:14px;font-size:16px;font-weight:700;cursor:pointer;
    background:linear-gradient(180deg,var(--rose),var(--rose2));color:#2a0a17}
  .ghost{background:none;border:1px solid var(--line);color:var(--ink);font-weight:600}
  .box{background:#2a1a28;border:1px solid var(--line);border-radius:14px;padding:12px;font-size:13.5px;line-height:1.5;margin-top:12px}
  .err{background:#3a1626;border:1px solid var(--rose2);border-radius:12px;padding:10px 12px;font-size:13.5px;margin-bottom:6px}
</style>
</head>
<body>
<main>
  <h1>🎴 Buka akses Sayogi Mesra</h1>
  <p>Sekali bayar, semua mode dan kartu terbuka untuk akun <b>{{ auth()->user()->email }}</b>.</p>

  @if (session('status'))
    <div class="box" role="status">{{ session('status') }}</div>
  @endif
  @foreach ($errors->all() as $error)
    <div class="err" role="alert">{{ $error }}</div>
  @endforeach

  <div class="harga">{{ $rp($tagihan->nominal) }}</div>
  <p>Transfer <b>persis</b> sejumlah ini. Tiga angka terakhir adalah kode unik supaya pembayaranmu cepat ketemu.</p>

  @if ($qris)
    <img class="qr" src="{{ (new chillerlan\QRCode\QRCode(new chillerlan\QRCode\QROptions(['outputType' => 'svg'])))->render($qris->payload) }}"
         alt="QRIS pembayaran {{ $rp($tagihan->nominal) }} atas nama {{ $penerima }}" width="240" height="240">
    <p>Pindai dari aplikasi bank atau e-wallet. Nominal sudah terisi; pastikan penerimanya atas nama <b>{{ $penerima }}</b>.</p>
  @else
    <div class="err">Pembayaran QRIS belum tersedia. Hubungi admin Sayogi Creative.</div>
  @endif

  @if ($tagihan->status === \App\Enums\StatusBayar::Dilaporkan)
    <div class="box">Laporanmu sudah masuk dan sedang dicek admin. Halaman ini otomatis membuka game setelah akses aktif.</div>
  @else
    <form method="post" action="/beli">
      @csrf
      <label for="pengirim">Nama pengirim di aplikasi pembayaranmu</label>
      <input id="pengirim" name="pengirim" value="{{ old('pengirim') }}" maxlength="100" autocomplete="name" required>
      <button type="submit">Saya sudah bayar</button>
    </form>
  @endif

  <form method="post" action="/logout">@csrf<button class="ghost" type="submit">Keluar</button></form>
</main>
@if ($tagihan->status === \App\Enums\StatusBayar::Dilaporkan)
<script>setTimeout(() => location.reload(), 60000)</script>
@endif
</body>
</html>
