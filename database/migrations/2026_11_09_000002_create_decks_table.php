<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('decks', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('jenis', 10); // game (Lucu & Nakal), manis
            $table->boolean('featured')->default(false);
            $table->unsignedInteger('urutan')->default(0); // antrean featured
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('deck_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deck_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('urutan')->default(0);
            $table->string('teks', 500);
            $table->text('keterangan')->nullable(); // cara main tantangan
            $table->string('alat')->nullable();
            $table->unsignedSmallInteger('detik')->nullable();
            $table->string('untuk', 10)->nullable(); // kartu manis: self, partner, both
            $table->unsignedTinyInteger('babak')->nullable(); // kartu manis: 1-3
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deck_cards');
        Schema::dropIfExists('decks');
    }
};
