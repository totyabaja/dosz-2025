<?php

namespace App\Models\Position;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PositionType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function subtypes(): HasMany
    {
        return $this->hasMany(PositionSubType::class);
    }
}
