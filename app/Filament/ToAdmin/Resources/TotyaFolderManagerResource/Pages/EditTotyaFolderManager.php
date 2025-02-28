<?php

namespace App\Filament\ToAdmin\Resources\TotyaFolderManagerResource\Pages;

use App\Filament\ToAdmin\Resources\TotyaFolderManagerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTotyaFolderManager extends EditRecord
{
    protected static string $resource = TotyaFolderManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
