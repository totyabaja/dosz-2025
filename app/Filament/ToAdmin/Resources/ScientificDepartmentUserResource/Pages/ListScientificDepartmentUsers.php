<?php

namespace App\Filament\ToAdmin\Resources\ScientificDepartmentUserResource\Pages;

use App\Filament\ToAdmin\Resources\ScientificDepartmentUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListScientificDepartmentUsers extends ListRecords
{
    protected static string $resource = ScientificDepartmentUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
