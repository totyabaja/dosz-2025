<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
