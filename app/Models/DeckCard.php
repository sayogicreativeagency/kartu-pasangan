<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeckCard extends Model
{
    protected $fillable = ['urutan', 'teks', 'keterangan', 'alat', 'detik', 'untuk', 'babak'];

    public function deck(): BelongsTo
    {
        return $this->belongsTo(Deck::class);
    }
}
