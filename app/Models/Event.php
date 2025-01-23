<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Event extends Model implements HasMedia
{
    use HasFactory, SoftDeletes;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'event_start_date',
        'event_end_date',
        'event_registration_start_datetime',
        'event_registration_end_datetime',
        'event_registration_available',
        'event_registration_editable',
    ];

    protected $casts = [
        'id' => 'string', // UUID használata
        'name' => 'array',
        'description' => 'array',
        'event_start_date' => 'date',
        'event_end_date' => 'date',
        'event_registration_start_datetime' => 'datetime',
        'event_registration_end_datetime' => 'datetime',
        'event_registration_available' => 'boolean',
        'event_registration_editable' => 'boolean',
    ];

    protected $keyType = 'string';

    public $incrementing = false; // UUID nem numerikus

    public static function booted()
    {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function getEventNameAttribute()
    {
        return $this->name[session()->get('locale', 'hu')];
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getMedia('event-images')?->first()?->getUrl() ?? $this->getMedia('event-images')?->first()?->getUrl('thumb') ?? null;
    }

    public function getEventDocuments()
    {
        return $this->getMedia('event-documents')?->map(function ($item) {
            return [
                'url' => $item?->getUrl() ?? null,
                'name' => $item?->custom_properties['label'] ?? null,
            ];
        });
    }

    public function scopeRegIsActive($query)
    {
        $query
            ->where(function ($query) {
                return $query
                    ->where('event_registration_available', true)
                    ->whereDate('event_registration_end_datetime', '>=', now());
            });
    }

    public function event_registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function event_custom_forms(): HasMany
    {
        return $this->hasMany(EventCustomForm::class);
    }

    public function reg_form(): HasOne
    {
        return $this->hasOne(EventCustomForm::class)
            ->where('type', 'reg');
    }
}
