<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StatusBayar: string implements HasColor, HasLabel
{
    case Menunggu = 'menunggu';     // tagihan dibuat, pembeli belum lapor bayar
    case Dilaporkan = 'dilaporkan'; // pembeli sudah lapor, menunggu dicek admin
    case Lunas = 'lunas';
    case Ditolak = 'ditolak';

    public function getLabel(): string
    {
        return match ($this) {
            self::Menunggu => 'Belum dilaporkan',
            self::Dilaporkan => 'Menunggu dicek',
            self::Lunas => 'Lunas',
            self::Ditolak => 'Ditolak',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Menunggu => 'gray',
            self::Dilaporkan => 'warning',
            self::Lunas => 'success',
            self::Ditolak => 'danger',
        };
    }

    public function terbuka(): bool
    {
        return in_array($this, [self::Menunggu, self::Dilaporkan], true);
    }
}
