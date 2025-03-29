<?php

namespace App\Filament\Admin\Resources\Document\DocumentTemplateResource\Pages;

use App\Filament\Admin\Resources\Document\DocumentTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditDocumentTemplate extends EditRecord
{
    protected static string $resource = DocumentTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview_pdf')
                ->label('Preview PDF')
                ->action(fn($record) => DocumentTemplateResource::previewPdf($record)),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make()
                ->visible(fn() => Auth::user()->isSuperAdmin()),
            Actions\RestoreAction::make(),
        ];
    }
}
