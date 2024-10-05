<?php

namespace App\Models;

use Filament\Panel;
use App\Models\ObsSetting;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\PersonalAccessToken;
use Filament\Models\Contracts\HasTenants;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Althinect\FilamentSpatieRolesPermissions\Concerns\HasSuperAdmin;

class User extends Authenticatable implements HasTenants, FilamentUser, HasAvatar, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles, HasSuperAdmin, HasPanelShield;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
        ];
    }

    public function tournaments(): BelongsToMany
    {
        return $this->belongsToMany(Tournament::class, 'tournament_users');
    }

    public function getTenants(Panel $panel): array|Collection
    {
        return $this->tournaments;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->tournaments->contains($tenant);
    }

    // public function canAccessPanel(Panel $panel): bool
    // {
    //     if ($panel->getId() === 'admin') {
    //         return $this->name === "Super Admin";
    //     }

    //     if ($panel->getId() === 'studio') {
    //         return $this->name !== 'Super Admin';
    //     }

    //     if ($panel->getId() === 'api') {
    //         return true;
    //     }

    //     return true;
    // }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : null ;
    }

    /**
     * Get the obsSetting associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function obsSetting(): HasOne
    {
        return $this->hasOne(ObsSetting::class);
    }

//     public function tokens()
// {
//     return $this->hasMany(PersonalAccessToken::class, 'tokenable_id')
//     ->where('tokenable_type', self::class);
// }
}
