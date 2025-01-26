<?php

namespace App\Filament\Admin\Resources\Event\CustomFormResource\Pages;

use App\Filament\Admin\Resources\Event\CustomFormResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomForms extends ListRecords
{
    protected static string $resource = CustomFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
