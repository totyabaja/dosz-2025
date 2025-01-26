<?php

namespace App\Filament\Admin\Resources\Event\CustomFormResource\Pages;

use App\Filament\Admin\Resources\Event\CustomFormResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomForm extends EditRecord
{
    protected static string $resource = CustomFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
