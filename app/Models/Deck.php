<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Deck buatan admin. Biasa = kartunya dicampur acak ke tumpukan; featured = kartunya keluar berurutan. */
class Deck extends Model
{
    protected $fillable = ['judul', 'jenis', 'featured', 'urutan', 'aktif'];

    protected function casts(): array
    {
        return ['featured' => 'boolean', 'aktif' => 'boolean'];
    }

    public function cards(): HasMany
    {
        return $this->hasMany(DeckCard::class)->orderBy('urutan');
    }

    /**
     * Bentuk yang dibaca game: per jenis, deck featured teratas di antrean (urut) atau kartu deck biasa (diacak game).
     *
     * @return array{game: array{featured: ?array, pool: array}, manis: array{featured: ?array, pool: array}}
     */
    public static function untukGame(): array
    {
        $decks = static::where('aktif', true)->with('cards')->orderBy('urutan')->orderBy('id')->get();
        $kartu = fn (Deck $deck) => $deck->cards->map(fn (DeckCard $c) => $deck->jenis === 'manis'
            ? ['t' => $c->teks, 'a' => $c->untuk ?: 'both', 'b' => $c->babak ?: 1]
            : ['t' => $c->teks, 'd' => (string) $c->keterangan, 'alat' => $c->alat ?: '—', 'sec' => $c->detik ?: 60])->all();

        $hasil = [];
        foreach (['game', 'manis'] as $jenis) {
            $milik = $decks->where('jenis', $jenis);
            $featured = $milik->firstWhere('featured', true);
            $hasil[$jenis] = [
                'featured' => $featured && $featured->cards->isNotEmpty() ? $kartu($featured) : null,
                'pool' => $milik->where('featured', false)->flatMap($kartu)->values()->all(),
            ];
        }

        return $hasil;
    }
}
