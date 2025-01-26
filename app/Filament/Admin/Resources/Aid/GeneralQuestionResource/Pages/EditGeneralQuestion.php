<?php

namespace App\Filament\Admin\Resources\Aid\GeneralQuestionResource\Pages;

use App\Filament\Admin\Resources\Aid\GeneralQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGeneralQuestion extends EditRecord
{
    protected static string $resource = GeneralQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
