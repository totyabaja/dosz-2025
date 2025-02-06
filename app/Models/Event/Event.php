<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use TotyaDev\TotyaDevMediaManager\Traits\InteractsWithMediaFolders;

class Event extends Model implements HasMedia
{
    use HasFactory, SoftDeletes;
    use InteractsWithMedia;
    use InteractsWithMediaFolders;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'event_start_date',
        'event_end_date',
        'event_registration_start_datetime',
        'event_registration_end_datetime',
        'event_registration_available',
        'abstract_neccessary',
        'event_registration_editable',
        'event_reg_form_id',
        'event_feedback_form_id',
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
        $avatarUrl = $this->getMedia('event-banners')?->first()?->getUrl()
            ?? $this->getMedia('event-banners')?->first()?->getUrl('thumb');

        if (!$avatarUrl) {
            $settings = app(\App\Settings\GeneralSettings::class);
            $avatarUrl = Storage::url($settings->brand_logo);
        }

        return $avatarUrl;
    }

    // TODO
    public function documents()
    {
        return $this->getMedia('event-documents');
    }

    // TODO
    public function getEventDocuments()
    {
        return $this->getMedia('event-documents')?->map(function ($item) {
            return [
                'url' => $item?->getUrl() ?? null,
                'name' => $item?->custom_properties['label-' . session()->get('locale', 'hu')] ?? null,
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

    public function reg_form(): BelongsTo
    {
        return $this->belongsTo(CustomForm::class, 'event_reg_form_id', 'id');
    }

    public function feedback_form(): BelongsTo
    {
        return $this->belongsTo(CustomForm::class, 'event_feedback_form_id', 'id');
    }

    public function registerMediaConversions(Media|null $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }
}
