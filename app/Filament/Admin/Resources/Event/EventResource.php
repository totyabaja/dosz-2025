<?php

namespace App\Filament\Admin\Resources\Event;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Admin\Resources\Event\EventResource\Pages;
use App\Filament\Admin\Resources\Event\EventResource\Widgets\EventStatistics;
use App\Models\Event\Event;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use TotyaDev\TotyaDevMediaManager\Form\MediaManagerInput;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'event_name';

    public static function getModelLabel(): string
    {
        return __('resource.title.event');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.title.events');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make()
                    ->contained(false)
                    ->persistTabInQueryString('event-tabs')
                    ->schema([
                        Forms\Components\Tabs\Tab::make('Infók')
                            ->icon('heroicon-o-information-circle')
                            ->schema([

                                Forms\Components\Section::make('Infók')
                                    ->aside(fn($operation) => $operation == 'create')
                                    ->description('Egy nyelv megadása kötelező. Amennyiben mindkettőt kitölti, mindkét nyelven elérhető lesz a tartalom.')
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('filament_avatar_url')
                                            ->hiddenLabel()
                                            ->disk('public')
                                            ->collection('event-banners')
                                            ->alignCenter()
                                            ->columnSpanFull(),

                                        Forms\Components\Grid::make([
                                            'default' => 1,
                                            'lg' => 2,
                                        ])->schema([

                                            Forms\Components\TextInput::make('name.hu')
                                                ->label(mb_ucfirst(__('resource.components.name_hungarian')))
                                                ->required(fn($get): bool => $get('name.en') == '' || ($get('name.hu') != '' && $get('name.en') != ''))
                                                ->live(debounce: 500)
                                                ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                                            Forms\Components\TextInput::make('name.en')
                                                ->label(mb_ucfirst(__('resource.components.name_english')))
                                                ->required(fn($get): bool => $get('name.hu') == '')
                                                ->live(debounce: 500)
                                                ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set, Forms\Get $get) => $operation === 'create' ? ($get('name.hu') == '' ? $set('slug', Str::slug($state)) : null) : null),
                                            Forms\Components\TextInput::make('slug')
                                                ->label(mb_ucfirst(__('resource.components.slug')))
                                                ->required()
                                                ->unique(Event::class, 'slug', ignoreRecord: true)
                                                ->maxLength(255)
                                                ->prefix(fn() => url('/event') . '/')
                                            /*->suffixAction(
                                                ActionsAction::make('ss')
                                                    ->icon('heroicon-o-clipboard')
                                                    ->tooltip('Másolás vágólapra')
                                                    ->action(function ($state, $livewire) {
                                                        $url = url('/event').'/'.$state;
                                                        $livewire->js(
                                                            'window.navigator.clipboard.writeText("'.$url.'");
                                                            $tooltip("Copied to clipboard", { timeout: 1500 }); '
                                                        );
                                                    })
                                            )*/,
                                            Forms\Components\Tabs::make()
                                                ->persistTabInQueryString(true)
                                                ->schema([
                                                    Forms\Components\Tabs\Tab::make(mb_ucfirst(__('resource.tabs.hungarian')))->schema([
                                                        TinyEditor::make('description.hu')
                                                            ->label(mb_ucfirst(__('resource.components.description_hungarian')))
                                                            ->fileAttachmentsDisk('public')
                                                            ->fileAttachmentsVisibility('public')
                                                            ->fileAttachmentsDirectory('uploads') // TODO
                                                            ->profile('default')
                                                            ->columnSpan('full')
                                                            ->required(fn($get): bool => $get('name.hu') != ''),
                                                    ]),
                                                    Forms\Components\Tabs\Tab::make(mb_ucfirst(__('resource.tabs.english')))->schema([
                                                        TinyEditor::make('description.en')
                                                            ->label(mb_ucfirst(__('resource.components.description_english')))
                                                            ->fileAttachmentsDisk('public')
                                                            ->fileAttachmentsVisibility('public')
                                                            ->fileAttachmentsDirectory('uploads') // TODO
                                                            ->profile('default')
                                                            ->columnSpan('full')
                                                            ->required(fn($get): bool => $get('name.en') != ''),
                                                    ]),

                                                ])->columnSpanFull(),
                                        ]),
                                    ])
                                    ->headerActions([
                                        fn(string $operation): Forms\Components\Actions\Action => Forms\Components\Actions\Action::make('save')
                                            ->label(__('filament-actions::edit.single.modal.actions.save.label'))
                                            ->action(function (Forms\Components\Section $component, EditRecord $livewire) {
                                                $livewire->saveFormComponentOnly($component);

                                                Notification::make()
                                                    ->title(__('resource.messages.saved_title', ['item' => __('resource.title.description')]))
                                                    ->body(__('resource.messages.saved_body', ['item' => __('resource.title.description')]))
                                                    ->success()
                                                    ->send();
                                            })
                                            ->visible($operation === 'edit'),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Időpontok')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                Forms\Components\Section::make('Időpontok')
                                    ->aside()
                                    ->description(new HtmlString(<<<'HTML'
                                            <b>
                                                Rendezvény időpontja</b>
                                            <p>
                                                A rendezvény kezdő és végpontja. Eshetnek egy napra.
                                            </p>
                                            <b>Regisztrációs idősáv</b>
                                            <p>
                                                A regisztráció lehetőségére nyitva álló időablak.</p>
                                        HTML))
                                    ->columns([
                                        'default' => 1,
                                        'md' => 2,
                                    ])
                                    ->schema([
                                        ...static::getTimepointsComponents(),
                                    ])
                                    ->footerActions([
                                        fn(string $operation): Forms\Components\Actions\Action => Forms\Components\Actions\Action::make('save')
                                            ->label(__('filament-actions::edit.single.modal.actions.save.label'))
                                            ->action(function (Forms\Components\Section $component, EditRecord $livewire) {
                                                $livewire->saveFormComponentOnly($component);

                                                Notification::make()
                                                    ->title(__('resource.messages.saved_title', ['item' => __('resource.title.timepoint')]))
                                                    ->body(__('resource.messages.saved_body', ['item' => __('resource.title.timepoint')]))
                                                    ->success()
                                                    ->send();
                                            })
                                            ->visible($operation === 'edit'),
                                    ]),
                            ]),

                    ])->columnSpanFull(),
            ]);
    }


    // TODO: az egészet át kell alakítani



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('filament_avatar_url')
                    ->label('Image')
                    ->disk('public')
                    ->collection('event-banners')
                    ->wrap(),
                Tables\Columns\TextColumn::make('name')
                    ->label(mb_ucfirst(__('resource.components.title')))
                    ->listWithLineBreaks()
                    ->searchable(),
                Tables\Columns\TextColumn::make('event_start_date')
                    ->label(mb_ucfirst(__('resource.components.event_start_date')))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event_end_date')
                    ->label(mb_ucfirst(__('resource.components.event_end_date')))
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('event_registration_available')
                    ->label(mb_ucfirst(__('resource.components.event_registration_available')))
                    ->boolean(),
                Tables\Columns\IconColumn::make('event_registration_editable')
                    ->label(mb_ucfirst(__('resource.components.event_registration_available')))
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(mb_ucfirst(__('resource.components.created_at')))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(mb_ucfirst(__('resource.components.updated_at')))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(mb_ucfirst(__('resource.components.deleted_at')))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->recordUrl(fn($record) => EventResource::getUrl('view', ['record' => $record]));
    }

    public static function getTimepointsComponents(): array
    {
        return [
            Forms\Components\DatePicker::make('event_start_date')
                ->label(mb_ucfirst(__('resource.components.event_start_date')))
                ->native(false)
                ->format('Y-m-d')
                ->locale('hu')
                ->displayFormat('Y. F. d., l')
                ->closeOnDateSelection()
                ->live()
                ->prefixIcon('heroicon-o-calendar')
                ->required(),
            Forms\Components\DatePicker::make('event_end_date')
                ->label(mb_ucfirst(__('resource.components.event_end_date')))
                ->native(false)
                ->format('Y-m-d')
                ->locale('hu')
                ->displayFormat('Y. F. d., l')
                ->closeOnDateSelection()
                ->live()
                ->prefixIcon('heroicon-o-calendar')
                ->required()
                ->afterOrEqual('event_start_date'), // Nem lehet korábbi, mint a kezdő dátum
            Forms\Components\DateTimePicker::make('event_registration_start_datetime')
                ->label(mb_ucfirst(__('resource.components.event_registration_start_datetime')))
                ->native(false)
                ->format('Y-m-d H:i')
                ->seconds(false)
                ->displayFormat('Y. F. d., l H:i')
                ->locale('hu')
                ->maxDate(fn($get) => $get('event_start_date') ?? null)
                ->closeOnDateSelection()
                ->live()
                ->prefixIcon('heroicon-o-calendar')
                ->required()
                ->before(fn($get) => $get('event_start_date')), // Csak a rendezvény előtt kezdődhet
            Forms\Components\DateTimePicker::make('event_registration_end_datetime')
                ->label(mb_ucfirst(__('resource.components.event_registration_end_datetime')))
                ->native(false)
                ->format('Y-m-d H:i')
                ->displayFormat('Y. F. d., l H:i')
                ->seconds(false)
                ->locale('hu')
                ->maxDate(fn($get) => $get('event_start_date') ?? null)
                ->closeOnDateSelection()
                ->live()
                ->prefixIcon('heroicon-o-calendar')
                ->required()
                ->before(fn($get) => Carbon::parse($get('event_start_date'))) // Legkésőbb a rendezvény előtti napon
                ->afterOrEqual(fn($get) => $get('event_registration_start_datetime')), // Csak a regisztráció kezdete után lehet

            Forms\Components\Toggle::make('event_registration_available')
                ->label(mb_ucfirst(__('resource.components.event_registration_available')))
                ->required(),
            Forms\Components\Toggle::make('event_registration_editable')
                ->label(mb_ucfirst(__('resource.components.event_registration_editable')))
                ->required(),
        ];
    }

    public static function getDocumentsComponents(): array
    {
        return [
            SpatieMediaLibraryFileUpload::make('media')
                ->hiddenLabel(true)
                ->collection('event-documents')
                ->disk('public')
                ->required()
                ->multiple(),
            /*MediaManagerInput::make('event-documents')
                ->disk('public')
                ->hiddenLabel(true)
                ->schema([
                    Forms\Components\TextInput::make('label')
                        ->label('resource.components.title')
                        ->required()
                        ->live(debounce: 500),
                ])
                ->itemLabel(fn($state) => $state['label'])
                ->defaultItems(1)
                ->reorderable()
                ->collapsible()
                ->collapsed(true)
                ->required()
                ->columnSpanFull(),
                */
        ];
    }

    public static function getPersonalDataProtectionDocumentsComponents(): array
    {
        return [
            MediaManagerInput::make('event-datasec-documents')
                ->disk('public')
                ->hiddenLabel(true)
                ->schema([
                    Forms\Components\TextInput::make('label')
                        ->label('resource.components.title')
                        ->required()
                        ->live(debounce: 500),
                ])
                ->itemLabel(fn($state) => $state['label'])
                ->defaultItems(1)
                ->maxItems(1)
                ->collapsed(true)
                ->required()
                ->reorderable(false)
                ->columnSpanFull(),
        ];
    }

    public static function getRecordSubNavigation($page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewEvent::class,
            Pages\EditEvent::class,
            Pages\ManageEventRegistrations::class,
            Pages\ManageEventForms::class,
            Pages\ManageEventStatistics::class,
            Pages\EventDocuments::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'view' => Pages\ViewEvent::route('/{record}'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
            'registrations' => Pages\ManageEventRegistrations::route('/{record}/registrations'),
            'forms' => Pages\ManageEventForms::route('/{record}/forms'),
            'statistics' => Pages\ManageEventStatistics::route('/{record}/statistics'),
            'documents' => pages\EventDocuments::route('/{record}/documents')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationGroup(): ?string
    {
        return __('menu.nav_group.content');
    }

    public static function getNavigationSort(): ?int
    {
        return 0;
    }
}
