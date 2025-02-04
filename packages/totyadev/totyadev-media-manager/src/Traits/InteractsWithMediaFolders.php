<?php

namespace TotyaDev\TotyaDevMediaManager\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use TotyaDev\TotyaDevMediaManager\Models\Folder;

trait InteractsWithMediaFolders
{
    public function folders(): MorphToMany
    {
        return $this->morphToMany(Folder::class, 'model', 'folder_has_models', 'model_id', 'folder_id');
    }

    public function myFolders(): MorphMany
    {
        return $this->morphMany(Folder::class, 'user');
    }
}
