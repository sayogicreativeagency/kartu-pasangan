<?php

namespace App\Filament\Resources\Decks;

use App\Filament\Resources\Decks\Pages\CreateDeck;
use App\Filament\Resources\Decks\Pages\EditDeck;
use App\Filament\Resources\Decks\Pages\ListDecks;
use App\Models\Deck;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

/** Deck kartu buatan admin: biasa = dicampur acak ke tumpukan game, featured = dimainkan berurutan (teratas di antrean). */
class DeckResource extends Resource
{
    protected static ?string $model = Deck::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $modelLabel = 'deck';

    protected static ?string $pluralModelLabel = 'deck kartu';

    public static function form(Schema $schema): Schema
    {
        $jenis = fn (string $nilai) => fn (Get $get) => $get('../../jenis') === $nilai;

        return $schema->columns(1)->components([
            TextInput::make('judul')->required()->maxLength(255),
            Select::make('jenis')
                ->options(['game' => 'Tantangan (Permainan Lucu & Nakal)', 'manis' => 'Kartu Manis'])
                ->required()->live()->default('game'),
            Toggle::make('featured')
                ->helperText('Featured: kartunya keluar sesuai urutan di bawah. Kalau ada beberapa, yang dipakai deck featured teratas di antrean. Biasa: kartunya dicampur acak.'),
            Toggle::make('aktif')->default(true),
            Repeater::make('cards')
                ->label('Kartu')
                ->relationship()
                ->orderColumn('urutan')
                ->reorderable()
                ->collapsible()
                ->itemLabel(fn (array $state) => $state['teks'] ?? null)
                ->schema([
                    Textarea::make('teks')->label('Isi kartu / judul tantangan')->required()->maxLength(500)->rows(2),
                    Textarea::make('keterangan')->label('Cara main')->rows(2)->visible($jenis('game')),
                    TextInput::make('alat')->placeholder('— (tanpa alat)')->visible($jenis('game')),
                    TextInput::make('detik')->numeric()->minValue(5)->maxValue(600)->default(60)->visible($jenis('game')),
                    Select::make('untuk')
                        ->options(['self' => 'Yang menarik', 'partner' => 'Pasangannya', 'both' => 'Berdua'])
                        ->default('both')->visible($jenis('manis')),
                    Select::make('babak')->options([1 => 'Babak 1', 2 => 'Babak 2', 3 => 'Babak 3'])->default(1)->visible($jenis('manis')),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('urutan')
            ->defaultSort('urutan')
            ->description('Seret untuk mengatur antrean deck featured.')
            ->columns([
                TextColumn::make('judul')->searchable(),
                TextColumn::make('jenis')->badge()->formatStateUsing(fn (string $state) => $state === 'manis' ? 'Kartu Manis' : 'Tantangan'),
                TextColumn::make('cards_count')->counts('cards')->label('Kartu'),
                ToggleColumn::make('featured'),
                ToggleColumn::make('aktif'),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDecks::route('/'),
            'create' => CreateDeck::route('/create'),
            'edit' => EditDeck::route('/{record}/edit'),
        ];
    }
}
