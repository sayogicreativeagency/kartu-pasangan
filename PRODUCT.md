# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

Web app PWA Laravel. Paket APK nanti hanya membungkus web yang sama, bahasa desainnya tidak native.

## Users

Pasangan dewasa (18+) yang sudah menikah, di Indonesia. Biasanya memakai satu HP berdua, di rumah, saat waktu berdua. Tujuannya mendekatkan diri lewat permainan, bukan berlomba.

## Product Purpose

Sayogi Mesra adalah permainan kartu untuk pasangan: tarik kartu, ikuti perintah atau main mini game-nya, giliran bergantian. Berhasil kalau pasangan merasa lebih dekat setelah satu sesi.

## Positioning

Permainannya intim tetapi punya pagar yang nyata: Mode Nakal hanya terbuka kalau dua-duanya setuju, kata aman menghentikan permainan kapan saja tanpa denda, dan setiap sesi ditutup peluk 20 detik. Isi kartunya bisa ditulis sendiri oleh pasangan.

## Operating Context

- Tamu di web bisa mencoba game tanpa akun; datanya lokal di HP.
- App terinstal selalu mulai dari layar login, lalu langsung ke game kalau sesinya masih ada. App terinstal tidak boleh menampilkan landing.
- Banyak game memakai alat rumah (gelas, koin, karet, bantal) dan ada mode tanpa alat.

## Capabilities and Constraints

- Tiga mode: Kartu Manis (tiga babak, jeda ngobrol tiap lima kartu, tanpa skor), Permainan Lucu (26 mini game, kalah kena hukuman lucu), Permainan Nakal (mini game, hukuman langsung, helai, token, hadiah untuk juara).
- Akun menyimpan data game terenkripsi, sehingga ikut ke HP mana pun pasangan login.
- Tamu bisa mencoba 3 kartu di `/coba`; kartu ke-4 meminta daftar dan beli.
- Akses penuh: Rp70.000 sekali bayar lewat QRIS DANA dengan nominal unik, dicek manual admin di `/admin`. Modul langganan bulanan sudah disiapkan tetapi belum dijual. Jangan menulis klaim "gratis".
- Admin (Nizar) mengisi deck kartu di `/admin`: deck biasa dicampur acak, deck featured keluar berurutan.

## Brand Commitments

- Nama produk: Sayogi Mesra. Game-nya bernama Kartu Pasangan. Dibuat oleh Sayogi Creative.
- Bahasa Indonesia santai dan hangat, menggoda lewat nada, tidak eksplisit secara seksual.
- Penanda 18+ wajib terlihat jelas di landing, tanpa pop-up gerbang.
- Arah visual dari pemilik: nakal, valentine, penuh cinta, gelap dan pink manis, sedikit kinky, pasangan romantis.

## Evidence on Hand

- Isi kartu asli di `resources/views/game.blade.php` (GAMES, MANIS, HUK, HUK_LUCU, HADIAH) boleh dikutip sebagai contoh.
- Ikon di `public/icon-192.png` dan `public/icon-512.png`.
- Belum ada testimoni, jumlah pengguna, liputan, atau harga. Jangan mengarangnya.

## Product Principles

1. Persetujuan berdua selalu di depan, bukan catatan kaki.
2. Keintiman bukan lomba: skor dan hukuman melayani kedekatan.
3. Pasangan yang memegang kendali atas isi dan batasnya.
4. Godaan lewat nada dan imajinasi, bukan ketelanjangan.

## Accessibility & Inclusion

Dipakai di HP dalam cahaya redup, sering dengan satu tangan. Teks harus kontras dan target sentuh harus nyaman. Hormati `prefers-reduced-motion`.
