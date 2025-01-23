<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PositionSubtype extends Model
{
    use HasFactory;

    protected $fillable = ['position_type_id', 'name', 'order'];

    protected $casts = [
        'name' => 'array',
    ];

    public function position_type(): BelongsTo
    {
        return $this->belongsTo(PositionType::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getFilamentNameAttribute()
    {
        return $this->name[session()->get('locale', 'hu')] ?? '';
    }
}
