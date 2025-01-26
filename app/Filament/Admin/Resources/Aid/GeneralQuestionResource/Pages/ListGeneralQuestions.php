<?php

namespace App\Filament\Admin\Resources\Aid\GeneralQuestionResource\Pages;

use App\Filament\Admin\Resources\Aid\GeneralQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGeneralQuestions extends ListRecords
{
    protected static string $resource = GeneralQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
