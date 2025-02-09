<?php

namespace App\Models\Scientific;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctoralSchool extends Model
{
    use  HasFactory, SoftDeletes;

    protected $fillable = [
        'university_id',
        'full_name',
        'short_name',
        'active',
        'url',
    ];

    protected $casts = [
        'full_name' => 'array',
        'active' => 'boolean',
    ];

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
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
