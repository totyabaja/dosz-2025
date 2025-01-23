<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFormElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'type',
        'title',
        'description',
        'tooltip',
    ];

    protected $casts = [
        'title',
        'description',
        'tooltip',
    ];

    public function form()
    {
        return $this->belongsTo(CustomForm::class);
    }
}
