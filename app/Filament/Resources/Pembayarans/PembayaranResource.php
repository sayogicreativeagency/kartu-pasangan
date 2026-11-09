<?php

namespace App\Filament\Resources\Pembayarans;

use App\Enums\StatusBayar;
use App\Filament\Resources\Pembayarans\Pages\ManagePembayarans;
use App\Models\Pembayaran;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

/** Admin mencocokkan nominal unik dengan mutasi QRIS, lalu menyetujui → akun dapat akses. */
class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $modelLabel = 'pembayaran';

    protected static ?string $pluralModelLabel = 'pembayaran';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        return Pembayaran::where('status', StatusBayar::Dilaporkan)->count() ?: null;
    }

    public static function table(Table $table): Table
    {
        $rp = fn (int $state) => 'Rp '.number_format($state, 0, ',', '.');

        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->label('Dibuat')->since()->sortable(),
                TextColumn::make('user.email')->label('Akun')->searchable()->copyable(),
                TextColumn::make('nominal')->formatStateUsing($rp)->searchable(),
                TextColumn::make('jenis')->formatStateUsing(fn (Pembayaran $record) => $record->jenis === 'langganan' ? "Langganan {$record->bulan} bln" : 'Sekali bayar'),
                TextColumn::make('pengirim')->placeholder('—'),
                TextColumn::make('status')->badge(),
                TextColumn::make('berlaku_sampai')->date()->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('status')->options(StatusBayar::class),
            ])
            ->recordActions([
                Action::make('setujui')
                    ->label('Setujui')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription(fn (Pembayaran $record) => 'Pastikan '.$rp($record->nominal).' sudah masuk ke DANA. Akun langsung mendapat akses.')
                    ->visible(fn (Pembayaran $record) => $record->status->terbuka())
                    ->action(function (Pembayaran $record) {
                        $record->setujui();
                        Notification::make()->success()->title('Akses '.$record->user->email.' aktif')->send();
                    }),
                Action::make('tolak')
                    ->label('Tolak')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Pembayaran $record) => $record->status->terbuka())
                    ->action(fn (Pembayaran $record) => $record->update(['status' => StatusBayar::Ditolak])),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePembayarans::route('/'),
        ];
    }
}
