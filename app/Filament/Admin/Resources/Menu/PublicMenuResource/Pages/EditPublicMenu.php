<?php

namespace App\Filament\Admin\Resources\Menu\PublicMenuResource\Pages;

use App\Filament\Admin\Resources\Menu\PublicMenuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPublicMenu extends EditRecord
{
    protected static string $resource = PublicMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
