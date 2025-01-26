<?php

namespace App\Models\ModifiedModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use TomatoPHP\FilamentMediaManager\Models\Folder as ModelsFolder;

class Folder extends ModelsFolder
{
    protected static function boot()
    {
        parent::boot(); // Az eredeti `boot` metódus meghívása, hogy a global scope ne vesszen el
    }

    public function folders(): MorphMany
    {
        return $this->morphMany(Folder::class, 'model');
    }

    public function parent(): MorphTo
    {
        return $this->morphTo('model');
    }
}
