<?php

namespace App\Models\Position;

use App\Models\Scientific\ScientificDepartment;
use App\Models\User;
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
        return $this->belongsTo(PositionSubtype::class);
    }

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scientific_department(): BelongsTo
    {
        return $this->belongsTo(ScientificDepartment::class);
    }
}
