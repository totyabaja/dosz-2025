<?php

namespace App\Filament\Admin\Resources\Menu\PublicMenuResource\Pages;

use App\Filament\Admin\Resources\Menu\PublicMenuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPublicMenus extends ListRecords
{
    protected static string $resource = PublicMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
