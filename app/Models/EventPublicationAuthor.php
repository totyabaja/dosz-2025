<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventPublicationAuthor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_publication_id',
        'author_order',
        'name',
        'affiliation',
        'email',
    ];

    protected $casts = [
        'author_order' => 'integer',
        'name' => 'array',
    ];

    public function publication(): BelongsTo
    {
        return $this->belongsTo(EventPublication::class);
    }
}
