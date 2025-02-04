<?php

namespace TotyaDev\TotyaDevMediaManager\Resources\FolderResource\Pages;

use TotyaDev\TotyaDevMediaManager\Resources\FolderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFolder extends EditRecord
{
    protected static string $resource = FolderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
