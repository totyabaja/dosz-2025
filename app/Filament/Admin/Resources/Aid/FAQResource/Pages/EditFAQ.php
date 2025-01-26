<?php

namespace App\Filament\Admin\Resources\Aid\FAQResource\Pages;

use App\Filament\Admin\Resources\Aid\FAQResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFAQ extends EditRecord
{
    protected static string $resource = FAQResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
