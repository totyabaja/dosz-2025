<?php

namespace App\Filament\Admin\Resources\Position\PositionTypeResource\Pages;

use App\Filament\Admin\Resources\Position\PositionTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPositionType extends EditRecord
{
    protected static string $resource = PositionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
