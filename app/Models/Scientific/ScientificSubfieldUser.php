<?php

namespace App\Models\Scientific;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ScientificSubfieldUser extends Pivot
{
    protected $table = 'scientific_subfield_user';

    protected $fillable = [
        'scientific_subfield_id',
        'user_id',
        'keywords',
    ];

    protected $casts = [
        'keywords' => 'array', // Automatikus JSON/Array konverzió
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scientific_subfield(): BelongsTo
    {
        return $this->belongsTo(ScientificSubfield::class);
    }
}
