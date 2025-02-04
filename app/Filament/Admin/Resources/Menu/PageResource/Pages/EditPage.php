<?php

namespace App\Filament\Admin\Resources\Menu\PageResource\Pages;

use App\Filament\Admin\Resources\Menu\PageResource;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
