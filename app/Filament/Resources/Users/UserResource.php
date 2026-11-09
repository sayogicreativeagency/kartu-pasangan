<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

/** Superadmin membuat akun dan mengangkat admin pengisi deck (mis. Nizar). */
class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $modelLabel = 'akun';

    protected static ?string $pluralModelLabel = 'akun';

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->isSuperadmin();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            TextInput::make('name')->label('Nama')->required()->maxLength(255),
            TextInput::make('email')->email()->required()->maxLength(255)->unique(ignoreRecord: true),
            TextInput::make('password')->password()->revealable()
                ->label(fn (?User $record) => $record ? 'Password baru (kosongkan kalau tidak diganti)' : 'Password')
                ->required(fn (?User $record) => ! $record)->minLength(8)
                ->dehydrated(fn (?string $state) => filled($state)),
            Toggle::make('is_admin')->label('Admin pengisi deck')
                ->helperText('Bisa mengisi deck kartu di /admin dan main tanpa bayar. Tidak bisa melihat pembayaran atau mengatur akun.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable(),
                TextColumn::make('email')->searchable()->copyable(),
                IconColumn::make('superadmin')->label('Superadmin')->boolean()->state(fn (User $record) => $record->isSuperadmin()),
                ToggleColumn::make('is_admin')->label('Admin deck')->disabled(fn (User $record) => $record->isSuperadmin()),
                IconColumn::make('akses')->label('Akses main')->boolean()->state(fn (User $record) => $record->punyaAkses()),
                TextColumn::make('created_at')->label('Daftar')->since()->sortable(),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUsers::route('/'),
        ];
    }
}
