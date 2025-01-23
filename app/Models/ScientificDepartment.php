<?php

namespace App\Models;

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

    protected $fillable = ['name_hu', 'name_en', 'slug'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'scientific_department_user');
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getMedia('to-avatars')?->first()?->getUrl() ?? $this->getMedia('to-avatars')?->first()?->getUrl('thumb') ?? null;
    }

    public function getNameAttribute()
    {
        return $this->{'name_'.(session()->get('locale', 'hu'))};
    }
}
