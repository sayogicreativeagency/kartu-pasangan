<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/** Iklan produk Sayogi Sedia yang muncul saat kartu menyebut barangnya (dicocokkan lewat kata kunci). */
class ProdukPendukung extends Model
{
    protected $table = 'produk_pendukung';

    protected $fillable = ['nama', 'gambar', 'tautan', 'kata_kunci', 'pengganti', 'aktif'];

    protected function casts(): array
    {
        return ['aktif' => 'boolean'];
    }

    /** @return list<array{nama: string, gambar: string, tautan: ?string, kata: list<string>, pengganti: ?string}> */
    public static function untukGame(): array
    {
        return static::where('aktif', true)->get()->map(fn (self $p) => [
            'nama' => $p->nama,
            'gambar' => Storage::disk('public')->url($p->gambar),
            'tautan' => $p->tautan,
            'kata' => array_values(array_filter(array_map(fn ($k) => mb_strtolower(trim($k)), explode(',', $p->kata_kunci)))),
            'pengganti' => $p->pengganti,
        ])->all();
    }
}
