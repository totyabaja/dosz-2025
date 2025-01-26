<?php

namespace App\Filament\Admin\Resources\Position\PositionResource\Pages;

use App\Filament\Admin\Resources\Position\PositionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPosition extends EditRecord
{
    protected static string $resource = PositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
