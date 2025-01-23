<?php

namespace App\Models;

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
        'url',
    ];

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
