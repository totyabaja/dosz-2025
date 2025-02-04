<?php

namespace App\Filament\Admin\Resources\Event\EventResource\Pages;

use App\Filament\Admin\Resources\Event\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            //$this->getSaveFormAction(),
            //$this->getCancelFormAction(),
        ];
    }
}
