<?php

namespace App\Models\Aid;

use App\Models\Scientific\{DoctoralSchool, University};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LegalAid extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * A kitölthető mezők.
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'university_id',
        'doctoral_school_id',
        'question',
    ];

    /**
     * Kapcsolat az egyetemmel.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Kapcsolat a doktori iskolával.
     */
    public function doctoral_school()
    {
        return $this->belongsTo(DoctoralSchool::class);
    }
}
