<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;

class University extends Model
{
    use  HasFactory, SoftDeletes;
    use InteractsWithMedia;

    protected $fillable = [
        'full_name',
        'full_name_en',
        'short_name',
        'url',
        'intezmenyi_szabalyzat',
    ];

    public function doctoral_schools(): HasMany
    {
        return $this->hasMany(DoctoralSchool::class);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getMedia('avatars')?->first()?->getUrl() ?? $this->getMedia('avatars')?->first()?->getUrl('thumb') ?? null;
    }
}
