<?php

namespace App\Filament\Admin\Resources\Event\EventStatusResource\Pages;

use App\Filament\Admin\Resources\Event\EventStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventStatuses extends ListRecords
{
    protected static string $resource = EventStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
