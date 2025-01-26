<?php

namespace App\Filament\Admin\Resources\Aid\LegalAidResource\Pages;

use App\Filament\Admin\Resources\Aid\LegalAidResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLegalAid extends ViewRecord
{
    protected static string $resource = LegalAidResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
