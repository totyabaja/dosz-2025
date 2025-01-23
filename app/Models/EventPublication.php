<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EventPublication extends Model
{
    use HasFactory;

    protected $fillable = [
        'publication_order',
        'event_registration_id',
        'publication_type',
    ];

    protected $casts = [
        'publication_order' => 'integer',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(EventRegistration::class);
    }

    public function authors(): HasMany
    {
        return $this->hasMany(EventPublicationAuthor::class)->orderBy('author_order');
    }

    public function abstract(): HasOne
    {
        return $this->hasOne(EventPublicationAbstract::class);
    }
}
