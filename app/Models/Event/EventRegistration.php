<?php

namespace App\Models\Event;

use App\Models\Scientific\DoctoralSchool;
use App\Models\User;
use App\Observers\EventRegistrationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([EventRegistrationObserver::class])]
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
        'doctoral_school_id', // TODO
        'scientific_department_id', // TODO
        'reg_form_response',
        'feedback_form_response',
        'accepted_data_protection',
        'accepted_data_use',
    ];

    protected $casts = [
        'event_invoice_address' => 'array',
        'reg_form_response' => 'array',
        'feedback_form_response' => 'array',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(EventRegistrationStatus::class)
            ->orderBy('created_at', 'asc');
    }

    public function status(): HasOne
    {
        return $this->hasOne(EventRegistrationStatus::class)
            ->latestOfMany();
    }

    function doctoral_school(): BelongsTo
    {
        return $this->belongsTo(DoctoralSchool::class);
    }

    public function publications(): HasMany
    {
        return $this->hasMany(EventPublication::class)
            ->orderBy('publication_order');
    }

    public function event_reg_form(): BelongsTo
    {
        return $this->belongsTo(CustomForm::class, 'event_reg_form_id', 'id');
    }
}
