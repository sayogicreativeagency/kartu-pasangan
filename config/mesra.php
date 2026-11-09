<?php

return [
    // Email yang boleh masuk /admin, dipisah koma. Diisi di .env, bukan di kode.
    'admin_emails' => array_filter(array_map('trim', explode(',', (string) env('ADMIN_EMAILS', '')))),

    // Isi teks QRIS statis (diawali 000201), bukan gambar. Nominal unik ditambahkan saat tagihan dibuat.
    'qris' => env('QRIS_PAYLOAD'),

    'harga_sekali' => (int) env('HARGA_SEKALI', 70000),

    // Langganan per bulan; kosong = belum dijual.
    'harga_langganan' => env('HARGA_LANGGANAN') ? (int) env('HARGA_LANGGANAN') : null,

    // Kartu yang boleh ditarik tamu di /coba sebelum diminta daftar.
    'batas_coba' => 3,
];
