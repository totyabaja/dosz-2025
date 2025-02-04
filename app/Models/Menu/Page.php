<?php

namespace App\Models\Menu;

use Biostate\FilamentMenuBuilder\Traits\Menuable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'version',
        'livewire_component_top',
        'livewire_component_bottom',
    ];

    protected $casts = [
        'name' => 'json',
        'description' => 'json',
    ];

    public function scopeWithMaxVersion($query, $slug)
    {
        return $query->where('slug', $slug)->max('version');
    }

    public function menu_page(): BelongsToMany
    {
        return $this->belongsToMany(PublicMenu::class, 'public_menu_page');
    }
}
