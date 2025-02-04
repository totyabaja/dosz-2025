<?php

namespace App\Filament\Admin\Resources\Event\EventResource\Pages;

use App\Filament\Admin\Resources\Event\EventResource;
use App\Models\Event\Event;
use Closure;
use Filament\Actions\Concerns\HasForm;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\Page;
use Filament\Support\Exceptions\Halt;
use TotyaDev\TotyaDevMediaManager\Form\MediaManagerInput;

class EventDocuments extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    public function form(Form $form): Form
    {
        //$folder = $this->record;
        // TODO: a event_documents-be szeretnék egy almappát neki

        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Section::make('Dokumentumok')
                    ->columns([
                        'default' => 1,
                    ])
                    ->schema([
                        MediaManagerInput::make('event-documents')
                            ->hiddenLabel(true)
                            ->disk('public')
                            ->schema([
                                Forms\Components\TextInput::make('label-hu')
                                    ->label(__('resource.components.name_hungarian'))
                                    ->required()
                                    ->live(debounce: 500),
                                Forms\Components\TextInput::make('label-en')
                                    ->label(__('resource.components.name_english')),
                            ])
                            ->itemLabel(fn($state) => $state['label-hu'] ?? 'Névtelen dokumentum')
                            ->defaultItems(1)
                            ->collapsed(true)
                            ->reorderable(true)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }
}
