<?php

namespace App\Filament\Admin\Resources\Scientific\UniversityResource\Pages;

use App\Filament\Admin\Resources\Scientific\UniversityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUniversity extends EditRecord
{
    protected static string $resource = UniversityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
