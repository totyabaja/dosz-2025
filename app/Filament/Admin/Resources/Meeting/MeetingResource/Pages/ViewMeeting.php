<?php

namespace App\Filament\Admin\Resources\Meeting\MeetingResource\Pages;

use App\Filament\Admin\Resources\Meeting\MeetingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMeeting extends ViewRecord
{
    protected static string $resource = MeetingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
