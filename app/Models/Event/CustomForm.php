<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomForm extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'description', 'content'];

    protected $casts = ['content' => 'array'];

    public function form_elements(): HasMany
    {
        return $this->hasMany(CustomFormElement::class);
    }

    public function event_custom_forms(): HasMany
    {
        return $this->hasMany(EventCustomForm::class);
    }

    // TODO: töröl
    public function event_form_responses(): HasMany
    {
        return $this->hasMany(EventFormResponse::class);
    }
}
