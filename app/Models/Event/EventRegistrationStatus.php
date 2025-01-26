<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EventRegistrationStatus extends Pivot
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'event_registration_id',
        'event_status_id',
        'comment',
    ];

    protected $casts = [
        'comment' => 'array',
    ];

    public function eventRegistration(): BelongsTo
    {
        return $this->belongsTo(EventRegistration::class);
    }

    public function eventStatus(): BelongsTo
    {
        return $this->belongsTo(EventStatus::class);
    }
}
