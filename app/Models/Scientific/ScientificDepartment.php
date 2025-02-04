<?php

namespace App\Models\Scientific;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use TotyaDev\TotyaDevMediaManager\Traits\InteractsWithMediaFolders;

class ScientificDepartment extends Model implements HasMedia
{
    use HasFactory, SoftDeletes;
    use InteractsWithMedia;
    use InteractsWithMediaFolders;

    protected $fillable = [
        'name',
        'slug',
        'is_active'
    ];

    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'scientific_department_user');
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getMedia('to-avatars')?->first()?->getUrl() ?? $this->getMedia('to-avatars')?->first()?->getUrl('thumb') ?? null;
    }

    public function getFilamentNameAttribute()
    {
        return $this->name[session()->get('locale', 'hu')];
    }

    public function scopeActive($query)
    {
        $query->where('is_active', true);
    }
}
