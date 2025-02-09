<?php

namespace App\Models\Scientific;

use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class University extends Model implements HasMedia, HasAvatar
{
    use HasFactory, SoftDeletes;
    use InteractsWithMedia;

    protected $fillable = [
        'full_name',
        'short_name',
        'url',
        'intezmenyi_szabalyzat',
    ];

    protected $casts = [
        'full_name' => 'array',
    ];

    public function doctoral_schools(): HasMany
    {
        return $this->hasMany(DoctoralSchool::class);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getMedia('university-avatars')?->first()?->getUrl() ?? $this->getMedia('university-avatars')?->first()?->getUrl('thumb') ?? null;
    }

    public function getFilamentFullNameAttribute()
    {
        return $this->full_name[session()->get('locale', 'hu')];
    }

    public function scopeActive($query)
    {
        $query->where('active', true);
    }
}
