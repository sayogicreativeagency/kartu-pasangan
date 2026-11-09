<?php

namespace App\Filament\Resources\ProdukPendukungs;

use App\Filament\Resources\ProdukPendukungs\Pages\ManageProdukPendukungs;
use App\Models\ProdukPendukung;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

/** Banner produk Sayogi Sedia yang tampil sebagai modal saat kartu menyebut barangnya. */
class ProdukPendukungResource extends Resource
{
    protected static ?string $model = ProdukPendukung::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?string $modelLabel = 'produk pendukung';

    protected static ?string $pluralModelLabel = 'produk pendukung';

    protected static ?string $navigationLabel = 'Produk Pendukung';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            TextInput::make('nama')->required()->maxLength(255)->placeholder('Borgol bulu'),
            FileUpload::make('gambar')->label('Banner iklan')->image()->disk('public')->directory('produk')
                ->maxSize(2048)->required()->helperText('Gambar landscape, maksimal 2 MB.'),
            TextInput::make('tautan')->label('Tautan produk')->url()->maxLength(255)->placeholder('https://sedia.sayogicreative.com/...'),
            TextInput::make('kata_kunci')->label('Kata kunci')->required()->maxLength(255)->placeholder('borgol, handcuff')
                ->helperText('Dipisah koma. Iklan muncul kalau isi kartu, cara main, alat, atau hukuman memuat salah satu kata ini.'),
            TextInput::make('pengganti')->label('Kalau nggak punya')->maxLength(255)->placeholder('Pakai tali atau ikat pinggang')
                ->helperText('Barang rumah pengganti, supaya kartunya tetap bisa dimainkan.'),
            Toggle::make('aktif')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('gambar')->disk('public')->label('Banner'),
                TextColumn::make('nama')->searchable(),
                TextColumn::make('kata_kunci')->label('Kata kunci'),
                ToggleColumn::make('aktif'),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageProdukPendukungs::route('/'),
        ];
    }
}
