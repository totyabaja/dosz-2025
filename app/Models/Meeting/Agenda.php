<?php

namespace App\Models\Meeting;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use TotyaDev\TotyaDevMediaManager\Traits\InteractsWithMediaFolders;

class Agenda extends Model implements HasMedia
{
    use HasFactory, HasUuids;
    use InteractsWithMedia;
    use InteractsWithMediaFolders;

    protected $fillable = [
        'name',
        'order',
        'meeting_id',
        'description',
    ];

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'agenda_user');
    }
}
