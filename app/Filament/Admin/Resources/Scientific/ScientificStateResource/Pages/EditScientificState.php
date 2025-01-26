<?php

namespace App\Filament\Admin\Resources\Scientific\ScientificStateResource\Pages;

use App\Filament\Admin\Resources\Scientific\ScientificStateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditScientificState extends EditRecord
{
    protected static string $resource = ScientificStateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
