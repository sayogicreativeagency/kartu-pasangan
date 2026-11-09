<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// is_admin fillable untuk form /admin; pendaftaran hanya meneruskan data tervalidasi (nama, email, password).
#[Fillable(['name', 'email', 'password', 'is_admin'])]
#[Hidden(['password', 'remember_token', 'game_state'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'game_state' => 'encrypted',
            'is_admin' => 'boolean',
        ];
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    /** Superadmin dari ADMIN_EMAILS: mengatur akun dan pembayaran. */
    public function isSuperadmin(): bool
    {
        return in_array(strtolower($this->email), array_map('strtolower', config('mesra.admin_emails')), true);
    }

    /** Admin mengisi deck kartu; diangkat superadmin lewat menu Akun. */
    public function isAdmin(): bool
    {
        return $this->isSuperadmin() || $this->is_admin;
    }

    public function punyaAkses(): bool
    {
        return $this->isAdmin() || $this->pembayaran()->aktif()->exists();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }
}
