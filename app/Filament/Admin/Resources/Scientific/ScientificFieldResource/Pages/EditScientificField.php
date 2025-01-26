<?php

namespace App\Filament\Admin\Resources\Scientific\ScientificFieldResource\Pages;

use App\Filament\Admin\Resources\Scientific\ScientificFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditScientificField extends EditRecord
{
    protected static string $resource = ScientificFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
