<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventRegistration extends Model
{
    use HasFactory, SoftDeletes;
    use HasUuids;

    protected $fillable = [
        'id',
        'event_id',
        'user_id',
        'event_invoice_address',
        'notification_email',
        'doctoral_school_id',
        'scientific_department_id',
    ];

    protected $casts = [
        'event_invoice_address' => 'array',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statuses()
    {
        return $this->hasMany(EventRegistrationStatus::class)->orderBy('created_at', 'asc');
    }

    public function status()
    {
        return $this->hasOne(EventRegistrationStatus::class)->latestOfMany();
    }

    public function publications(): HasMany
    {
        return $this->hasMany(EventPublication::class)->orderBy('publication_order');
    }

    public function event_form_response(): HasOne
    {
        return $this->hasOne(EventFormResponse::class);
    }
}
