<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use  HasFactory, SoftDeletes, HasUuids;

    protected $fillable = ['user_id', 'street', 'city', 'postal_code', 'country'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
