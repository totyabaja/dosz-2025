<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventFormResponse extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'event_registration_id',
        'custom_form_id',
        'responses',
    ];

    protected $casts = [
        'responses' => 'array',
    ];

    public function event_registration(): BelongsTo
    {
        return $this->belongsTo(EventRegistration::class);
    }

    public function custom_form(): BelongsTo
    {
        return $this->belongsTo(CustomForm::class);
    }
}
