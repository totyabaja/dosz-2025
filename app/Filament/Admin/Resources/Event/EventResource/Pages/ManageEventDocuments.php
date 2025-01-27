<?php

namespace App\Filament\Admin\Resources\Event\EventResource\Pages;

use App\Filament\Admin\Resources\Event\EventResource;
use App\Models\Event\Event;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Infolists;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Actions\Concerns\InteractsWithRecords;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use TomatoPHP\FilamentMediaManager\Form\MediaManagerInput;
use TomatoPHP\FilamentMediaManager\Models\Media;

class ManageEventDocuments extends Page implements HasTable
{
    use InteractsWithRecord;
    use InteractsWithTable;
    use InteractsWithFormActions;

    protected static string $resource = EventResource::class;

    protected static string $view = 'filament.admin.resources.event.event-resource.pages.manage-event-documents';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public static function table(Table $table): table
    {
        return $table
            ->query(
                fn($livewire) => Media::query()
                    ->whereHasMorph(
                        'model',
                        [Event::class],
                        function ($query)  use ($livewire) {
                            $query
                                ->where('id', $livewire->record->id)
                                ->where('collection_name', 'event-documents');
                        }
                    )
            )
            ->columns([
                Tables\Columns\TextColumn::make('custom_properties.label'),
                Tables\Columns\TextColumn::make('mime_type'),
            ])
            ->actions([
                ViewAction::make()
                    ->infolist([
                        Infolists\Components\TextEntry::make('name'),
                    ]),
                DeleteAction::make(),
            ])
            ->headerActions([
                Actions\CreateAction::make('documentsAction')
                    ->label(__('resource.buttons.documents'))
                    ->icon('heroicon-o-document')
                    //->color('warning')
                    ->button()
                    ->form([
                        MediaManagerInput::make('event-documents')
                            ->disk('public')
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->label(__('resource.components.title'))
                                    ->required()
                                    ->live(debounce: 500),
                            ])
                            ->itemLabel(fn($state) => $state['label'])
                            ->defaultItems(1)
                            ->reorderable()
                            ->required()
                            ->deletable(false)
                            ->columnSpanFull(),
                    ])
                    ->action(function (array $data, $livewire) {
                        //dd($data, $livewiregetFormComponentFileAttachmentUrl);
                        $event = $livewire->record; // Ensure we have the correct Event model

                        // TODO: nem működik itt, ert ez egy array...
                        if (!empty($data['event-documents'])) {
                            foreach ($data['event-documents'] as $file) {
                                //dd($file);
                                $event->addMedia($file) // Use the correct path from MediaManagerInput
                                    ->toMediaCollection('event-documents');
                            }
                        }
                    }),
            ]);
    }
}
