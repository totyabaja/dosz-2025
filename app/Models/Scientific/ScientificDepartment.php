<?php

namespace App\Models\Scientific;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use TomatoPHP\FilamentMediaManager\Traits\InteractsWithMediaFolders;

class ScientificDepartment extends Model implements HasMedia
{
    use HasFactory, SoftDeletes;
    use InteractsWithMedia;
    use InteractsWithMediaFolders;

    protected $fillable = [
        'name',
        'slug',
        'is-active'
    ];

    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean',
    ];

    public function users()
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
}
