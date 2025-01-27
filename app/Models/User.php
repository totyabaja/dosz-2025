<?php

namespace App\Models;

use App\Models\Scientific;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail, HasAvatar, HasName, HasMedia
{
    use InteractsWithMedia;
    use HasUuids, HasRoles;
    use HasApiTokens, HasFactory, Notifiable;
    use TwoFactorAuthenticatable;
    use HasPanelShield;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'firstname',
        'lastname',
        'password',
        'email_intezmenyi',
        'mobil',
        'disszertacio',
        'kutatohely',
        'multi_tudomanyag',
        'tudfokozat',
        'fokozateve',
        'adatvedelmit_elfogadta',
        'scientific_state_id',
        'doctoral_school_id',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function booted(): void
    {
        parent::booted();
        static::created(function (User $user) {
            $user->assignRole('user');
        });
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }

    public function getUsernameAttribute(): string
    {
        return $this->name;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        switch ($panel->getId()) {
            case 'admin':
                return $this->hasAnyRole(['super_admin', 'dosz_admin', 'jogsegelyes', 'dosz_rendezvenyes']);
                break;
            case 'to_admin':
                return $this->hasAnyRole(['super_admin', 'dosz_admin', 'to_admin', 'to_rendezvenyes']);
                break;
            case 'event':
                return $this->hasRole('user');
                break;
        }

        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getMedia('avatars')?->first()?->getUrl() ?? $this->getMedia('avatars')?->first()?->getUrl('thumb') ?? null;
    }

    public function onlyNativeUser(): bool
    {
        // Ellenőrizzük, hogy pontosan egy elem van-e és az 'user'
        return count($this->roles) === 1 && $this->roles[0] === 'user';
    }

    // Define an accessor for the 'name' attribute
    public function getNameAttribute()
    {
        switch (session()->get('locale', 'hu')) {
            case 'hu':
                return "{$this->lastname} {$this->firstname}";
                break;

            case 'en':
                return "{$this->firstname} {$this->lastname}";
                break;
        }
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(config('filament-shield.super_admin.name'));
    }

    public function registerMediaConversions(Media|null $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }


    public function doctoral_school(): BelongsTo
    {
        return $this->belongsTo(Scientific\DoctoralSchool::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(Address::class)->latestOfMany();
    }

    public function scientific_state(): BelongsTo
    {
        return $this->belongsTo(Scientific\ScientificState::class);
    }

    public function scientific_subfields(): BelongsToMany
    {
        return $this->belongsToMany(Scientific\ScientificSubfield::class)
            ->withPivot('keywords')
            ->withTimestamps();
    }

    public function scientific_fields_users(): HasMany
    {
        return $this->hasMany(Scientific\ScientificSubfieldUser::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position\Position::class);
    }

    public function scientific_department_users(): HasMany
    {
        return $this->hasMany(Scientific\ScientificDepartmentUser::class);
    }

    public function scientific_departments(): BelongsToMany
    {
        return $this->belongsToMany(Scientific\ScientificDepartment::class)
            ->withPivot([
                'accepted',
                'request_datetime',
                'acceptance_datetime',
            ])
            ->withTimestamps();
    }

    public function currentDepartment(): ?Scientific\ScientificDepartment
    {
        return Scientific\ScientificDepartment::find(session()->get('sd_selected', null));
    }
}
