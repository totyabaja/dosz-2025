<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventPublicationAbstract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'language',
        'title',
        'abstract',
        'keywords',
        'event_publication_id',
    ];

    protected $casts = [
        'title' => 'array',
        'abstract' => 'array',
        'keywords' => 'array',
    ];

    public function publication(): BelongsTo
    {
        return $this->belongsTo(EventPublication::class);
    }
}
