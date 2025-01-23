<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ScientificDepartmentUser extends Pivot
{
    protected $fillable = [
        'user_id',
        'scientific_department_id',
        'accepted',
        'request_datetime',
        'acceptance_datetime',
    ];

    public $table = 'scientific_department_user';

    protected $casts = [
        'accepted' => 'boolean',
        'request_datetime' => 'datetime',
        'acceptance_datetime' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scientific_department(): BelongsTo
    {
        return $this->belongsTo(ScientificDepartment::class);
    }
}
