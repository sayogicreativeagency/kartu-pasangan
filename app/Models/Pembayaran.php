<?php

namespace App\Models;

use App\Enums\StatusBayar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Satu tagihan: sekali bayar (akses selamanya) atau langganan per bulan (berlaku sampai tanggal tertentu). */
class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = ['jenis', 'bulan', 'nominal', 'status', 'pengirim', 'dilaporkan_at'];

    protected function casts(): array
    {
        return [
            'status' => StatusBayar::class,
            'dilaporkan_at' => 'datetime',
            'lunas_at' => 'datetime',
            'berlaku_sampai' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeTerbuka(Builder $query): void
    {
        $query->whereIn('status', [StatusBayar::Menunggu, StatusBayar::Dilaporkan]);
    }

    /** Lunas dan masih berlaku: sekali bayar tidak punya batas, langganan sampai berlaku_sampai. */
    public function scopeAktif(Builder $query): void
    {
        $query->where('status', StatusBayar::Lunas)
            ->where(fn (Builder $q) => $q->whereNull('berlaku_sampai')->orWhere('berlaku_sampai', '>', now()));
    }

    /** Kode unik 1-999 di belakang harga supaya admin bisa mencocokkan mutasi QRIS. */
    public static function nominalUnik(int $dasar): int
    {
        do {
            $nominal = $dasar + random_int(1, 999);
        } while (static::terbuka()->where('nominal', $nominal)->exists());

        return $nominal;
    }

    public function setujui(): void
    {
        $berlaku = null;
        if ($this->jenis === 'langganan') {
            // Perpanjangan disambung dari akhir langganan yang masih jalan.
            $akhir = $this->user->pembayaran()->aktif()->where('jenis', 'langganan')->max('berlaku_sampai');
            $berlaku = ($akhir ? now()->max($akhir) : now())->addMonths($this->bulan ?: 1);
        }

        $this->forceFill(['status' => StatusBayar::Lunas, 'lunas_at' => now(), 'berlaku_sampai' => $berlaku])->save();
    }
}
