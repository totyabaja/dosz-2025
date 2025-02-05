<?php

namespace App\Filament\Admin\Resources\Event\EventStatusResource\Pages;

use App\Filament\Admin\Resources\Event\EventStatusResource;
use App\Models\Event\EventStatus;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventStatus extends EditRecord
{
    protected static string $resource = EventStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave()
    {
        return redirect()->to(EventStatusResource::getUrl());
    }
}
