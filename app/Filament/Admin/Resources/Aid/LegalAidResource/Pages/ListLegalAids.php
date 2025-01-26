<?php

namespace App\Filament\Admin\Resources\Aid\LegalAidResource\Pages;

use App\Filament\Admin\Resources\Aid\LegalAidResource;
use Filament\Resources\Pages\ListRecords;

class ListLegalAids extends ListRecords
{
    protected static string $resource = LegalAidResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
