<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_type_id',
        'position_subtype_id',
        'notes',
        'start_date',
        'end_date',
        'user_id',
        'email',
        'scientific_department_id',
        'areas',
    ];

    public function position_subtype(): BelongsTo
    {
        return $this->belongsTo(PositionSubType::class);
    }
}
