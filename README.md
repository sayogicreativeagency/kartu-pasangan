# 🎴 Kartu Pasangan

PWA (Progressive Web App) kartu permainan untuk **pasangan suami istri** — tarik kartu, ikuti
perintahnya / main mini-gamenya, giliran bergantian.

> 💗 Dibuat untuk **keintiman berdua**, bukan lomba. Aturan pentingnya:
> jangan tersinggung atau marah — kalau mulai kesal, **stop, peluk pasangan, dingin dulu**.
> Ada **kata aman** yang bisa dipanggil kapan pun, tanpa penjelasan dan tanpa denda.

## 3 Mode

| Mode | Isi |
|---|---|
| 🎴 **Kartu Manis** | Kartu perintah romantis, 3 babak, jeda ngobrol 1 menit tiap 5 kartu. Tanpa skor. |
| 🎮 **Permainan Lucu** | 26 mini game pakai alat rumah (gelas, koin, karet, bantal…) atau tanpa alat. Menang = 1 poin, kalah dapat hukuman lucu. |
| 🔥 **Permainan Nakal** | Mini game + hukuman langsung kalau gagal. Manajemen "helai", token, kata aman, dan hadiah buat juara. |

## Aturan inti

- **Poin cuma buat yang menang.** Satu kartu = satu ronde → yang menang dapat 1 poin.
- **Nggak bisa jawab = 0 poin** (lawan juga nggak dapat). Di mode Nakal, yang gagal langsung
  menjalankan 1 kartu hukuman.
- **Duluan sampai skor maksimal = juara** → dapat hadiah pilihan. Kartu habis tapi seri?
  Tarik **1 kartu penentu**.
- **🚫 Token ada harganya:** pakai token (lewati kartu / batalkan hukuman) = **wajib buang 1 helai**.
- **🔒 Kata aman gratis:** sebut = berhenti total, tanpa potongan, tanpa alasan.
- **🫂 Penutup:** setiap sesi diakhiri sesi **peluk 20 detik** + pengingat bahwa permainan ini
  hanya untuk keintiman kalian berdua.

## Cara pakai

1. Buka alamatnya di browser HP (Chrome/Safari): tampil landing, bisa langsung dicoba tanpa akun.
2. Menu browser → **Tambahkan ke layar utama** (Add to Home Screen) → jadi seperti aplikasi.
3. Dari aplikasi: login dulu. Setelah itu sesinya tersimpan di HP, jadi tiap dibuka langsung ke game.
4. Isi nama kalian, pilih mode, jumlah helai, baca penjelasan + aturan penting, mulai main.

**Tanpa akun (landing), data disimpan lokal di HP itu saja** (`localStorage`), tidak dikirim ke server.
**Setelah login, data disimpan terenkripsi di akun** (MySQL), jadi ikut ke HP mana pun kalian login.

Service worker membuat app tetap jalan **offline** setelah dibuka sekali.

## Alur halaman

| URL | Isi |
|---|---|
| `/` | Landing (game, data lokal). Kalau terbuka dari aplikasi terinstal, dialihkan ke `/app` |
| `/app` | `start_url` aplikasi. Belum login → `/login`; sudah login → game, data sinkron ke akun |
| `/login`, `/register` | Akun. Remember cookie selalu aktif, jadi sesi tetap ada di HP |

## Pasang lokal (Herd + MySQL)

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
herd link sayogi-mesra
herd secure sayogi-mesra
```

Isi kredensial `DB_*` di `.env` sebelum `migrate`. Game ada di `resources/views/game.blade.php`,
service worker di `public/sw.js` (naikkan nama cache-nya kalau asset berubah).

## Isi kartu bisa kalian tulis sendiri

Tab 🃏 **Isi** → pilih tumpukan (permainan, hukuman per game, hadiah, hukuman lucu, kartu manis
babak 1–3) → ubah/tambah/hapus → **Simpan**. Ada juga pengaturan jumlah helai, target poin, dan
mode **Tanpa alat**.

---

Dibuat dengan ❤️ — [Sayogi Creative](https://sayogicreativeagency.github.io/landing/)
