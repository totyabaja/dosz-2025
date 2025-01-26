<?php

namespace App\Filament\Admin\Resources\Scientific\ScientificDepartmentResource\Pages;

use App\Filament\Admin\Resources\Scientific\ScientificDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListScientificDepartments extends ListRecords
{
    protected static string $resource = ScientificDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
