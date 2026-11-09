<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('jenis', 10)->default('sekali'); // sekali, langganan
            $table->unsignedTinyInteger('bulan')->nullable(); // khusus langganan
            $table->unsignedInteger('nominal'); // harga + kode unik 3 digit
            $table->string('status', 12)->default('menunggu')->index();
            $table->string('pengirim', 100)->nullable();
            $table->timestamp('dilaporkan_at')->nullable();
            $table->timestamp('lunas_at')->nullable();
            $table->timestamp('berlaku_sampai')->nullable(); // kosong = selamanya
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
