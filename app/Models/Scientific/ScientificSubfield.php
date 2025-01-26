<?php

namespace App\Models\Scientific;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ScientificSubfield extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'scientific_field_id'];

    public function field(): BelongsTo
    {
        return $this->belongsTo(ScientificField::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('keywords')
            ->withTimestamps();
    }
}
