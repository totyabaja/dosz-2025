<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventStatus extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    protected $casts = [
        'name' => 'array',
    ];
}
