<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk_pendukung', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('gambar'); // path banner di disk public
            $table->string('tautan')->nullable(); // halaman produk di Sayogi Sedia
            $table->string('kata_kunci'); // dipisah koma, dicocokkan ke isi kartu
            $table->string('pengganti')->nullable(); // barang rumah kalau tidak punya produknya
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk_pendukung');
    }
};
