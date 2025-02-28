<?php

namespace App\Filament\Admin\Resources\Meeting\MeetingResource\Pages;

use App\Filament\Admin\Resources\Meeting\MeetingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMeeting extends CreateRecord
{
    protected static string $resource = MeetingResource::class;
}
