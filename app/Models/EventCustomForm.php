<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventCustomForm extends Model
{
    protected $fillable = [
        'event_id',
        'custom_form_id',
        'type',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function custom_form(): BelongsTo
    {
        return $this->belongsTo(CustomForm::class);
    }
}
