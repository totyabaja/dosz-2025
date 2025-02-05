<?php

namespace App\Filament\Admin\Resources\Event\EventStatusResource\Pages;

use App\Filament\Admin\Resources\Event\EventStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEventStatus extends CreateRecord
{
    protected static string $resource = EventStatusResource::class;
}
