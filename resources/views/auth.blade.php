<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>{{ $mode === 'login' ? 'Masuk' : 'Daftar' }} · Sayogi Mesra</title>
<meta name="robots" content="noindex">
<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#170f16">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<style>
  :root{--bg:#170f16;--line:#3d2639;--ink:#fdeef5;--dim:#c39fb6;--rose:#ff7aa2;--rose2:#ff4d84}
  *{box-sizing:border-box}
  body{margin:0;min-height:100dvh;display:flex;align-items:center;justify-content:center;padding:24px 16px;
    background:var(--bg);color:var(--ink);font-family:system-ui,-apple-system,"Segoe UI",Roboto,sans-serif}
  form{width:100%;max-width:380px}
  h1{font-size:22px;margin:0 0 4px}
  p{color:var(--dim);font-size:14px;margin:0 0 18px}
  label{display:block;font-size:13px;color:var(--dim);margin:14px 0 6px}
  input{width:100%;background:#1d1220;border:1px solid var(--line);color:var(--ink);border-radius:12px;padding:13px;font-size:16px}
  input:focus{outline:2px solid var(--rose);outline-offset:1px}
  button{width:100%;margin-top:20px;padding:15px;border:0;border-radius:14px;font-size:16px;font-weight:700;cursor:pointer;
    background:linear-gradient(180deg,var(--rose),var(--rose2));color:#2a0a17}
  a{color:var(--rose)}
  .err{background:#3a1626;border:1px solid var(--rose2);border-radius:12px;padding:10px 12px;font-size:13.5px;margin-bottom:6px}
  .alt{text-align:center;margin:18px 0 0}
  @media (display-mode: standalone){.web-only{display:none}}
</style>
</head>
<body>
<form method="post" action="/{{ $mode }}">
  @csrf
  <h1>🎴 Sayogi Mesra</h1>
  <p>{{ $mode === 'login' ? 'Masuk buat lanjut main berdua.' : 'Buat akun, kartu kalian ikut ke HP mana pun.' }}</p>

  @if (session('status'))
    <div class="err" role="alert">{{ session('status') }}</div>
  @endif
  @foreach ($errors->all() as $error)
    <div class="err" role="alert">{{ $error }}</div>
  @endforeach

  @if ($mode === 'register')
    <label for="name">Nama</label>
    <input id="name" name="name" value="{{ old('name') }}" autocomplete="name" required autofocus>
  @endif

  <label for="email">Email</label>
  <input id="email" name="email" type="email" inputmode="email" value="{{ old('email') }}" autocomplete="username" required @if ($mode === 'login') autofocus @endif>

  <label for="password">Password</label>
  <input id="password" name="password" type="password" autocomplete="{{ $mode === 'login' ? 'current-password' : 'new-password' }}" required>

  @if ($mode === 'register')
    <label for="password_confirmation">Ulangi password</label>
    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
  @endif

  <button type="submit">{{ $mode === 'login' ? 'Masuk' : 'Daftar' }}</button>

  <p class="alt">
    @if ($mode === 'login')
      Belum punya akun? <a href="/register">Daftar</a>
    @else
      Sudah punya akun? <a href="/login">Masuk</a>
    @endif
  </p>
  <p class="alt web-only"><a href="/">Kembali ke beranda</a></p>
</form>
</body>
</html>
