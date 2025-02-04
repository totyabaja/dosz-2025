<?php

namespace App\Filament\Admin\Resources\Scientific\ScientificDepartmentResource\Pages;

use App\Filament\Admin\Resources\Scientific\ScientificDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use TotyaDev\TotyaDevMediaManager\Models\Folder;

class CreateScientificDepartment extends CreateRecord
{
    protected static string $resource = ScientificDepartmentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $parent_folder = Folder::where('collection', 'to-publikus-allomanyok')->first();
        // Új mappa létrehozása
        $folder = Folder::create([
            'model_id' => $parent_folder->id,
            'model_type' => Folder::class,
            'name' => $data['slug'] . " root folder",
            'collection' => $data['slug'] . "_root_folder",
        ]);

        // Új rekord létrehozása a fő modellben
        $record = static::getModel()::create($data);

        // A mappát a saját kapcsolatában mentjük
        $record->folders()->save($folder);

        return $record;
    }
}
