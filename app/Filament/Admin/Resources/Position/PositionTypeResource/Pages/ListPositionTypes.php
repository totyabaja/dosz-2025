<?php

namespace App\Filament\Admin\Resources\Position\PositionTypeResource\Pages;

use App\Filament\Admin\Resources\Position\PositionTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPositionTypes extends ListRecords
{
    protected static string $resource = PositionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
