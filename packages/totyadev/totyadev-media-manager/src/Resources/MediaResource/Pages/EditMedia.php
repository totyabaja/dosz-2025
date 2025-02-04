<?php

namespace TotyaDev\TotyaDevMediaManager\Resources\MediaResource\Pages;

use TotyaDev\TotyaDevMediaManager\Resources\MediaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMedia extends EditRecord
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
