<?php

namespace App\Models\Aid;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'question',
        'answer',
        'language',
        'is_visible'
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];
}
