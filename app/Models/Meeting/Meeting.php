<?php

namespace App\Models\Meeting;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'timepoint_start',
        'timepoint_end',
        'helye',
    ];

    protected $casts = [
        'timepoint_start' => 'datetime',
        'timepoint_end' => 'datetime',
    ];


    public function agendas(): HasMany
    {
        return $this->hasMany(Agenda::class);
    }
}
