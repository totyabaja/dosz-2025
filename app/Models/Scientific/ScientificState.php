<?php

namespace App\Models\Scientific;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScientificState extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
}
