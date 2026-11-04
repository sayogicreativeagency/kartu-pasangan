---
name: Sayogi Mesra
description: Permainan kartu untuk pasangan menikah 18+, dicetak flat dengan tiga tinta.
colors:
  aubergine: "#3a1238"
  pink: "#ff5a9e"
  blush: "#ffe3ee"
typography:
  display:
    fontFamily: "Rozha One, Bodoni 72, Didot, Georgia, serif"
    fontSize: "clamp(3.25rem, 5.6vw, 5.5rem)"
    fontWeight: 400
    lineHeight: 1
    letterSpacing: "-0.015em"
  headline:
    fontFamily: "Rozha One, Bodoni 72, Didot, Georgia, serif"
    fontSize: "clamp(2.6rem, 10.5vw, 3.75rem)"
    fontWeight: 400
    lineHeight: 0.98
    letterSpacing: "-0.015em"
  title:
    fontFamily: "Rozha One, Bodoni 72, Didot, Georgia, serif"
    fontSize: "clamp(1.5rem, 5.6vw, 2.25rem)"
    fontWeight: 400
    lineHeight: 1.1
  body:
    fontFamily: "Figtree, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "1.0625rem"
    fontWeight: 400
    lineHeight: 1.55
  button:
    fontFamily: "Figtree, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "1.0625rem"
    fontWeight: 700
    lineHeight: 1.55
  label:
    fontFamily: "Figtree, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "0.9375rem"
    fontWeight: 700
    lineHeight: 1.3
  handwritten:
    fontFamily: "Homemade Apple, Segoe Script, Bradley Hand, cursive"
    fontSize: "1.25rem"
    fontWeight: 400
    lineHeight: 1.75
  stamp:
    fontFamily: "Rozha One, Bodoni 72, Didot, Georgia, serif"
    fontSize: "1.625rem"
    fontWeight: 400
    lineHeight: 1.1
    letterSpacing: "0.05em"
rounded:
  label: "2px"
  plate: "3px"
  stamp: "6px"
  die-cut: "16px"
spacing:
  gutter: "20px"
  gutter-wide: "40px"
  section: "clamp(48px, 9vw, 110px)"
  gap-stack: "34px"
  gap-split: "56px"
  gap-wide: "72px"
components:
  button-primary:
    backgroundColor: "{colors.pink}"
    textColor: "{colors.aubergine}"
    typography: "{typography.button}"
    rounded: "{rounded.label}"
    padding: "12px 26px"
    height: "54px"
  button-primary-hover:
    backgroundColor: "{colors.blush}"
    textColor: "{colors.aubergine}"
  button-outline:
    textColor: "{colors.blush}"
    typography: "{typography.button}"
    rounded: "{rounded.label}"
    padding: "12px 26px"
    height: "54px"
  button-outline-hover:
    backgroundColor: "{colors.blush}"
    textColor: "{colors.aubergine}"
  button-outline-on-pink:
    textColor: "{colors.aubergine}"
    typography: "{typography.button}"
    rounded: "{rounded.label}"
    padding: "12px 26px"
    height: "54px"
  button-outline-on-pink-hover:
    backgroundColor: "{colors.aubergine}"
    textColor: "{colors.blush}"
  button-ink:
    backgroundColor: "{colors.aubergine}"
    textColor: "{colors.blush}"
    typography: "{typography.button}"
    rounded: "{rounded.label}"
    padding: "12px 26px"
    height: "54px"
  button-knock:
    textColor: "{colors.aubergine}"
    rounded: "{rounded.label}"
    padding: "8px 12px"
    height: "46px"
  button-knock-hover:
    backgroundColor: "{colors.aubergine}"
    textColor: "{colors.blush}"
  plate:
    backgroundColor: "{colors.blush}"
    textColor: "{colors.aubergine}"
    rounded: "{rounded.plate}"
    padding: "4px 10px"
  menu-row:
    textColor: "{colors.aubergine}"
    padding: "18px 12px"
  menu-row-selected:
    backgroundColor: "{colors.aubergine}"
    textColor: "{colors.blush}"
  order-slip:
    backgroundColor: "{colors.blush}"
    textColor: "{colors.aubergine}"
    padding: "30px 24px 24px"
  notice-sheet:
    backgroundColor: "{colors.blush}"
    textColor: "{colors.aubergine}"
    padding: "clamp(30px, 6vw, 64px) clamp(24px, 6vw, 72px)"
  notepad:
    backgroundColor: "{colors.blush}"
    textColor: "{colors.aubergine}"
    padding: "18px 20px 24px"
  door-hanger:
    backgroundColor: "{colors.pink}"
    textColor: "{colors.aubergine}"
    rounded: "{rounded.die-cut}"
    width: "max(220px, min(72vw, 300px, 34svh))"
  ink-stamp:
    textColor: "{colors.aubergine}"
    typography: "{typography.stamp}"
    rounded: "{rounded.stamp}"
    padding: "6px 14px 4px"
---

# Design System: Sayogi Mesra

## Overview

**Creative North Star: "Percetakan Kamar Bulan Madu"**

Semua yang tampil adalah barang cetakan dari sebuah kamar hotel bulan madu: gantungan pintu, menu layanan kamar, lembar peraturan, notes. Setiap barang dicetak flat dengan tiga tinta di atas bidang warna penuh, dengan huruf gemuk gaya hotel lama. Nadanya gelap dan pink manis, menggoda lewat kata dan cap bibir, tidak eksplisit. Pengguna sendiri memilih material ini setelah render 3D prosedural ditolak, jadi kerataan cetak adalah keputusan dunia, bukan keterbatasan.

Kedalaman datang dari cara kerja percetakan: bidang tinta, garis tinta, titik halftone yang meleset ke kanan bawah, dan tinta bertekstur pada cap. Gerak boleh hidup selama bendanya tetap kertas tipis: gantungan berayun dari lubang kenopnya dan terbalik untuk menampilkan kartu asli. Kepadatannya lapang di bidang, rapat di dalam barang cetakan, dengan target sentuh besar karena produk ini dipakai di HP dalam cahaya redup.

Cakupan: dunia ini baru berlaku pasti untuk landing di rute `/` (`resources/views/landing.blade.php`). Layar game (`resources/views/game.blade.php`) dan login (`resources/views/auth.blade.php`) masih memakai tampilan lama (latar `#170f16`, aksen mawar `#ff7aa2`, emas, hijau, sudut 20px, huruf sistem) dan belum dimigrasi. Itu drift yang diketahui; nilai lama itu bukan bagian dari sistem ini, dan migrasinya butuh keputusan tersendiri.

**Key Characteristics:**
- Tepat tiga tinta di bidang penuh, berganti antarbagian seperti lembar cetak yang berbeda.
- Huruf display gemuk Rozha One, teks kerja Figtree, tulisan tangan Homemade Apple hanya di barang yang memang ditulis tangan.
- Garis tinta 2px sebagai struktur; sudut nyaris tajam kecuali siluet die-cut.
- Bayangan selalu titik halftone tanpa blur, meleset ke kanan bawah.
- Motif cetak yang digambar sendiri: cap bibir, borgol kecil, hati, kunci, pita ornamen.

## Colors

Tiga tinta tanpa tinta keempat: satu gelap, satu panas, satu pucat, dan masing-masing cukup kuat untuk memenuhi seluruh bidang.

### Primary
- **Pink Panas** (#ff5a9e): tinta suara. Bidang penuh bagian menu dan penutup, badan gantungan pintu, tombol utama di bidang aubergine, garis kusen pintu, titik halftone di atas aubergine, garis notes.

### Neutral
- **Aubergine Malam** (#3a1238): tinta dasar. Latar halaman, warna tema PWA, semua teks di atas pink dan blush, tombol tinta di bidang pink, semua garis di atas kertas.
- **Blush Kertas** (#ffe3ee): tinta kertas. Teks di atas aubergine, lembar peraturan, slip pesanan, notes, plat 18+, dan keadaan hover tombol utama.

### Named Rules
**The Three Ink Rule.** Hanya tiga warna ini yang boleh dicetak. Tonal ramp di `.impeccable/design.json` hanya untuk panel, bukan izin menambah tint di layar. Pengecualian yang ada di build hanya dua keadaan sementara: lapisan aubergine transparan saat baris menu di-hover dan placeholder notes.

**The Ink Pairing Rule.** Teks hanya dicetak dalam tiga pasangan: blush di aubergine (13,2:1), aubergine di blush (13,2:1), aubergine di pink (5,4:1). Blush di pink hanya 2,4:1, jadi tidak pernah dipakai untuk teks. Di build ini pink tidak dipakai sebagai warna teks; ia bidang, garis, dan titik.

**The Full Field Rule.** Warna dipakai sebagai bidang selebar layar, bukan aksen kecil di latar netral. Antarbagian berpindah tinta (aubergine, pink, aubergine, pink), dipisah pita ornamen.

## Typography

**Display Font:** Rozha One (with Bodoni 72, Didot, Georgia, serif)
**Body Font:** Figtree 400 sampai 700 (with system-ui, -apple-system, Segoe UI, sans-serif)
**Label/Mono Font:** Homemade Apple untuk tulisan tangan (with Segoe Script, Bradley Hand, cursive)

**Character:** Rozha One adalah huruf gemuk kontras tinggi dari papan nama hotel lama; Figtree netral dan hangat, membawa semua kalimat kerja. Homemade Apple adalah tangan yang mengisi formulir hotel. Ketiganya di-host sendiri di `public/landing/fonts`.

### Hierarchy
- **Display** (400, clamp(3.25rem, 5.6vw, 5.5rem), 1): H1 hero di desktop. Di HP mengecil ke 1.75rem dengan line-height 1.08 agar hero muat di layar pertama, dan gantungan pintu mengambil suara terbesar.
- **Headline** (400, clamp(2.6rem, 10.5vw, 3.75rem), 0.98): judul bagian, dibatasi sekitar 12ch agar patah seperti papan nama.
- **Title** (400, clamp(1.5rem, 5.6vw, 2.25rem), 1.1): nama item menu. Judul pasal memakai varian sedikit lebih kecil, clamp(1.45rem, 4.4vw, 1.9rem) dengan 1.14.
- **Body** (400, 1.0625rem, 1.55): semua teks kerja, dibatasi 42 sampai 48ch. Kalimat persetujuan yang harus terbaca naik ke berat 600.
- **Button** (700, 1.0625rem, 1.55): tombol label cetak.
- **Label** (700, 0.9375rem, 1.3): keterangan level menu, judul slip, label form.
- **Handwritten** (400, 1.25rem, 1.75): isi kartu contoh di slip. Di notes ukurannya 1.0625rem pada baris 2.5rem.
- **Stamp** (400, 1.625rem, 1.1, 0.05em, huruf besar): teks cap tinta.

### Named Rules
**The Hand Means Hand Rule.** Homemade Apple hanya untuk teks yang dalam dunianya memang ditulis tangan: isi pesanan dan isi notes. Judul, tombol, dan keterangan tidak pernah memakainya.

**The Display Stays Regular Rule.** Rozha One hanya punya berat 400 dan kegemukannya sudah bawaan. Jangan menebalkannya secara sintetis, dan jangan memakainya untuk paragraf.

## Layout

Halaman adalah tumpukan bidang selebar layar dengan isi maksimum 1160px. Padding samping 20px di HP dan 40px mulai 900px; padding vertikal bagian mengikuti token `section`. Tumpukan satu kolom di HP menjadi dua kolom mulai 900px: menu 1.5fr dan 1fr, peraturan dua kolom sama lebar, notes dua kolom sama lebar. Di bawah 520px, baris menu melepas titik-titik pengarahnya dan level turun ke baris sendiri.

Hero landing dibagi kiri dan kanan di desktop atas permintaan eksplisit pengguna: kiri H1, keterangan, dan CTA; kanan pintu berkusen dengan gantungan, bukan mockup HP. Di HP urutannya bertumpuk: gantungan, H1, CTA dalam layar pertama, keterangan di bawahnya. Komposisi ini milik hero landing, bukan pola wajib untuk layar lain.

Target sentuh minimum 44px untuk tautan dan centang, 46px untuk tombol di gantungan, 54px untuk tombol utama. Bar atas menumpang di atas hero tanpa latar sendiri.

## Elevation & Depth

Sistem ini flat dan tidak punya bayangan blur sama sekali. Tumpukan dibaca dari urutan cetak: bidang di atas bidang, garis di atas bidang, dan titik halftone yang meleset ke kanan bawah di belakang barang cetakan. Warna titik selalu tinta yang kontras dengan bidang di bawahnya: pink di aubergine, aubergine di pink. Tinta bertekstur pada cap bibir dan cap BERHENTI berasal dari raster prosedural `public/landing/lips.webp` dan `stamp-ink.webp` yang dipakai sebagai mask, bukan foto.

### Shadow Vocabulary
- **Bayangan halftone** (`radial-gradient(circle, <tinta> 34%, transparent 37%) 0 0/9px 9px`, digeser sekitar 10px ke kanan bawah): di belakang gantungan, slip, dan notes. Stop gradiennya rapat sehingga hasilnya titik bertepi tegas, bukan transisi warna.
- **Garis dalam label** (`box-shadow: inset 0 0 0 4px <bidang>, inset 0 0 0 5.5px <garis>`): garis tipis cetak di dalam tombol utama dan tombol tinta. Blur nol, jadi ini garis, bukan bayangan.

### Named Rules
**The No Blur Rule.** Tidak ada `box-shadow` atau `filter` yang berblur. Depth dicetak, bukan dipancarkan. Gradien CSS bertepi tegas tetap sah sebagai alat untuk membuat titik halftone dan garis notes; yang dilarang adalah transisi warna yang terlihat.

**The Paper Thin Rule.** Benda boleh bergerak dalam ruang 3D selama tetap selembar kertas tanpa ketebalan, seperti gantungan yang berputar `rotateY`. Yang dilarang adalah tampilan benda 3D, bukan transform 3D.

## Shapes

Sudut nyaris tajam: label cetak 2px, plat 3px, cap 6px. Kelengkungan disimpan untuk siluet die-cut: gantungan berkepala bulat, berleher, berlubang kenop, dengan sudut bawah 16px. Tepi atas slip berlubang perforasi, lubang 7px setiap 26px. Lembar peraturan memakai garis kedua berjarak 6px di luar tepinya. Motif dimiringkan sedikit seperti dicap dengan tangan: bibir -13 derajat, borgol -9 derajat, cap -3 derajat.

**The Two Pixel Line Rule.** Struktur digambar dengan garis tinta 2px: kusen dan panel pintu, pemisah baris, pemisah pasal, tombol garis, dan pengarah bertitik di menu. Satu-satunya yang lebih berat adalah bingkai cap (3.5px); yang lebih halus adalah garis dalam tombol dan garis bawah tautan (1.5px).

## Components

### Buttons
Label cetak: bidang atau garis tinta dengan sudut nyaris tajam, tanpa bayangan.
- **Shape:** sudut 2px, tinggi minimum 54px, teks tengah berat 700.
- **Primary:** bidang pink, teks aubergine, garis dalam aubergine. Dipakai di bidang aubergine.
- **Ink:** bidang aubergine, teks blush, garis dalam blush. Padanan tombol utama di bidang pink dan kertas.
- **Outline:** garis 2px tanpa isi, blush di aubergine dan aubergine di pink, untuk tindakan kedua seperti Coba.
- **Hover / Focus:** naik 2px dalam .25s dengan easing `cubic-bezier(.16,1,.3,1)`; warna berganti dalam .2s; primary berubah blush, outline terisi penuh; tombol tinta juga miring -.6 derajat. Active turun kembali dan mengecil ke .98. Fokus memakai garis 2px berjarak 3px, blush di bidang gelap dan aubergine di kertas.
- **Knock:** tombol garis lebih kecil di dalam gantungan (minimum 46px); hover terisi aubergine.

### Chips
- **Style:** plat blush "Khusus 18+" dengan teks aubergine berat 700 dan sudut 3px, selalu terlihat di bar atas tanpa pop-up.
- **State:** statis, bukan kontrol.

### Cards / Containers
Barang cetakan, bukan kartu UI.
- **Corner Style:** tajam, dengan tepi khusus per barang (lihat Shapes).
- **Background:** blush dengan teks aubergine.
- **Shadow Strategy:** bayangan halftone untuk slip dan notes; lembar peraturan memakai garis kedua.
- **Border:** garis pemisah aubergine 2px di dalamnya.
- **Internal Padding:** slip 30px 24px 24px; notes 18px 20px 24px; peraturan mengikuti token `notice-sheet`.

### Inputs / Fields
- **Style:** notes bergaris tanpa bingkai. Textarea transparan di atas garis pink 2px setiap 2.5rem, teks tulisan tangan aubergine, tidak bisa di-resize.
- **Focus:** garis aubergine 2px berjarak 4px.
- **Error / Disabled:** belum ada di build; belum ditentukan.

### Navigation
- **Style:** bar atas menumpang di hero: wordmark Rozha One, plat 18+, lalu tautan Masuk atau Lanjut main di kanan dengan garis bawah dan tinggi minimum 44px.

### Menu Row
Baris menu layanan kamar di bidang pink: nama item Rozha One, pengarah bertitik 2px, level di kanan, deskripsi di bawah, dipisah garis 2px. Terpilih terisi aubergine dengan teks blush dan menjadi kontrol `aria-pressed`. Memilih baris mengganti isi slip pesanan di sebelahnya.

### Door Hanger
Tanda tangan dunia ini. Gantungan pink die-cut dengan kenop datar, bayangan halftone, huruf "Jangan Ganggu" setinggi 22,8% lebarnya, cap bibir, borgol kecil, dan tombol ketuk. Saat halaman dibuka ia berayun mereda dalam 1.9s dari lubang kenop. Saat diketuk ia berayun dan terbalik dalam .85s, menampilkan kartu asli dari game di sisi blush, dengan centang jenis kartu di baliknya. Dengan `prefers-reduced-motion`, ayunan dimatikan dan balikan terjadi seketika.

### Ink Stamp
Cap bingkai 3.5px, huruf besar Rozha One, miring -3 derajat, bertekstur lewat mask `stamp-ink.webp`. Dipakai untuk satu kata yang harus terasa resmi, seperti BERHENTI.

### Ornament Band
Pita setinggi 46px dari unit hati dan bibir 138px di antara bagian, dalam warna tinta lawan bidangnya, dekoratif dan `aria-hidden`.

**The Whole Unit Rule.** Pita ornamen selalu menampilkan unit utuh (`background-repeat: round`), jumlahnya menyesuaikan lebar dan tidak pernah terpotong di tepi layar.

## Do's and Don'ts

### Do:
- **Do** mulai dari bidang penuh salah satu dari tiga tinta, lalu cetak garis, teks, dan motif di atasnya.
- **Do** gambar motif baru sebagai bentuk flat satu tinta: SVG inline, atau raster prosedural yang dipakai sebagai mask dan mencatat asal-usulnya di sidecar `.json`.
- **Do** beri bayangan pada barang cetakan dengan titik halftone 9px yang meleset ke kanan bawah.
- **Do** pakai garis 2px untuk struktur dan sudut 2px untuk label cetak.
- **Do** jaga target sentuh minimum 44px dan hormati `prefers-reduced-motion`.
- **Do** tampilkan plat 18+ di landing tanpa pop-up gerbang.

### Don't:
- **Don't** menirukan benda 3D: pintu ber-render, kenop logam, pita satin, bevel, atau kilap. Render 3D sudah ditolak; tiruan benda hanya boleh kembali kalau pengguna memberikan gambar aslinya.
- **Don't** memakai gradien yang terlihat sebagai transisi warna atau bayangan berblur.
- **Don't** menambah tinta keempat atau tint permanen di luar tiga warna ini.
- **Don't** mencetak teks blush di atas pink (2,4:1).
- **Don't** memotong unit pita ornamen di tepi layar.
- **Don't** menyalin nilai dari `game.blade.php` atau `auth.blade.php` (latar `#170f16`, sudut 20px, emas, hijau, huruf sistem) ke permukaan dunia ini; itu tampilan lama yang belum dimigrasi.
