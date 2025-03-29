<?php

namespace App\Models\Document;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentTemplate extends Model
{
    use SoftDeletes, HasFactory;
    use HasUuids;

    protected $fillable = [
        'name',
        'parameters',
        'file_path',
        'content',
    ];

    protected $casts = [
        'parameters' => 'array',
    ];
}
