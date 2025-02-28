<?php

namespace App\Filament\Admin\Resources\Event;

use App\Filament\Admin\Resources\Event\CustomFormResource\Pages;
use App\Models\Event\CustomForm;
use App\Models\Scientific;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CustomFormResource extends Resource
{
    protected static ?string $model = CustomForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('resource.title.custom_form');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.title.custom_forms');
    }

    public static function customFormmainElements(): array
    {
        return [
            Forms\Components\Grid::make([
                'default' => 1,
                'md' => 4
            ])->schema([
                Forms\Components\Hidden::make('id')
                    ->default(fn(): string => Str::uuid()),
                Forms\Components\TextInput::make('title.hu')
                    ->label(mb_ucfirst(__('resource.components.name_hungarian')))
                    ->columnSpan(2)
                    ->required(),
                Forms\Components\TextInput::make('title.en')
                    ->label(mb_ucfirst(__('resource.components.name_english')))
                    ->columnSpan(2)
                    ->required(),
            ])
        ];
    }

    public static function optionalFormElements(): array
    {
        return [
            Forms\Components\Section::make(mb_ucfirst('További opciók'))
                ->columns([
                    'default' => 1,
                    'md' => 2
                ])
                ->schema([
                    Forms\Components\TextInput::make('helperText.hu')
                        ->label('resource.components.helperText_hu'),
                    Forms\Components\TextInput::make('helperText.en')
                        ->label('resource.components.helperText_en'),

                    Forms\Components\TextInput::make('hint.hu')
                        ->label('resource.components.hint_hu'),
                    Forms\Components\TextInput::make('hint.en')
                        ->label('resource.components.hint_en'),

                    Forms\Components\TextInput::make('placeholder.hu')
                        ->label('resource.components.placeholder_hu'),
                    Forms\Components\TextInput::make('placeholder.en')
                        ->label('resource.components.placeholder_en'),

                    Forms\Components\Toggle::make('required')
                        ->label('required')
                        ->required(),
                ])
                ->columns(2)
                ->collapsed()
                ->collapsible(),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(mb_ucfirst(__('Form neve')))
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label(mb_ucfirst(__('Form leírása')))
                    ->nullable(),

                Forms\Components\Builder::make('content')
                    ->blockNumbers(false)
                    //->blockPreviews()
                    ->collapsible(true)
                    ->collapsed(fn($operation) => $operation == 'edit')
                    ->reorderableWithButtons()
                    ->reorderableWithDragAndDrop(true)
                    ->cloneable()
                    ->blockPickerColumns(2)
                    ->label('Elem')
                    ->columnSpanFull()
                    ->blocks([
                        Forms\Components\Builder\Block::make('text_input')
                            ->icon('fas-font')
                            // ->preview('filament.form-content.block-previews.text-input')
                            ->schema([
                                ...static::customFormMainElements(),

                                Forms\Components\Select::make('type')
                                    ->options([
                                        'text' => 'text',
                                        'email' => 'email',
                                        'numeric' => 'numeric',
                                        'integer' => 'integer',
                                        'password' => 'password',
                                        'tel' => 'tel',
                                        'url' => 'url',
                                    ])
                                    ->required()
                                    ->default('text'),

                                ...static::optionalFormElements(),

                            ])
                            ->columns(2)
                            ->label(function (?array $state): string {
                                if ($state === null) {
                                    return mb_ucfirst(__('Text Input'));
                                }

                                return $state['title']['hu'] ?? mb_ucfirst(__('Text Input'));
                            }),
                        Forms\Components\Builder\Block::make('select')
                            ->icon('fas-list-check')
                            ->schema([
                                ...static::customFormMainElements(),

                                Forms\Components\Toggle::make('multiple')
                                    ->label('multi select'),

                                Forms\Components\Select::make('preload_options')
                                    ->label('pre_load_options')
                                    ->options([
                                        'scientific_departments' => 'Tudományos Osztályok',
                                        'scientific_fields' => 'Tudományterületek',
                                    ])
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('addPreLoaded')
                                            ->label(mb_ucfirst(__('resource.button.load')))
                                            ->action(function ($set, $state) {
                                                switch ($state) {
                                                    case 'scientific_departments':
                                                        $departments = Scientific\ScientificDepartment::active()
                                                            ->get()
                                                            ->map(function ($item) {
                                                                return [
                                                                    'id' => $item->id,
                                                                    'value' => [
                                                                        'hu' => $item->name['hu'],
                                                                        'en' => $item->name['en'],
                                                                    ],
                                                                ];
                                                            })->toArray();

                                                        $set('options', $departments);
                                                        break;
                                                    case 'scientific_fields':
                                                        $subfields = Scientific\ScientificField::all()
                                                            ->map(function ($item) {
                                                                return [
                                                                    'id' => $item->id,
                                                                    'value' => [
                                                                        'hu' => $item->name['hu'],
                                                                        'en' => $item->name['en'],
                                                                    ],
                                                                ];
                                                            })->toArray();
                                                        $set('options', $subfields);
                                                        break;
                                                }
                                            })
                                    ),

                                Forms\Components\Repeater::make('options')
                                    ->required()
                                    ->columnSpanFull()
                                    ->columns(5)
                                    ->schema([
                                        Forms\Components\TextInput::make('id')
                                            ->afterStateHydrated(fn($state) => Str::slug($state)),
                                        Forms\Components\TextInput::make('value.hu')
                                            ->label('érték HU')
                                            ->columnSpan(2),
                                        Forms\Components\TextInput::make('value.en')
                                            ->label('érték EN')
                                            ->columnSpan(2),
                                    ])
                                    ->minItems(1)
                                    ->itemLabel('opció'),

                                ...static::optionalFormElements(),
                            ])
                            ->columns(2)
                            ->label(function (?array $state): string {
                                if ($state === null) {
                                    return mb_ucfirst(__('Select'));
                                }

                                return $state['title']['hu'] ?? mb_ucfirst(__('Select'));
                            }),
                        Forms\Components\Builder\Block::make('checkbox')
                            ->icon('fas-square-check')
                            ->schema([
                                ...static::customFormMainElements(),

                                ...static::optionalFormElements(),
                            ])
                            ->columns(2)
                            ->label(function (?array $state): string {
                                if ($state === null) {
                                    return mb_ucfirst(__('Checkbox'));
                                }

                                return $state['title']['hu'] ?? mb_ucfirst(__('Checkbox'));
                            }),
                        Forms\Components\Builder\Block::make('toggle')
                            ->icon('fas-toggle-off')
                            ->schema([
                                ...static::customFormMainElements(),

                                ...static::optionalFormElements(),
                            ])
                            ->label(function (?array $state): string {
                                if ($state === null) {
                                    return mb_ucfirst(__('Toggle'));
                                }

                                return $state['title']['hu'] ?? mb_ucfirst(__('Toggle'));
                            }),
                        Forms\Components\Builder\Block::make('checkbox_list')
                            ->icon('fas-list-check')
                            ->schema([
                                ...static::customFormMainElements(),

                                ...static::optionalFormElements(),
                            ])
                            ->label(function (?array $state): string {
                                if ($state === null) {
                                    return mb_ucfirst(__('Checkbox list'));
                                }

                                return $state['title']['hu'] ?? mb_ucfirst(__('Checkbox list'));
                            }),
                        Forms\Components\Builder\Block::make('datetime_picker')
                            ->icon('fas-calendar')
                            ->schema([
                                ...static::customFormMainElements(),

                                ...static::optionalFormElements(),
                            ])
                            ->label(function (?array $state): string {
                                if ($state === null) {
                                    return mb_ucfirst(__('DateTime picker'));
                                }

                                return $state['title']['hu'] ?? mb_ucfirst(__('dateTime picker'));
                            }),
                        Forms\Components\Builder\Block::make('file_upload')
                            ->icon('fas-file-arrow-up')
                            ->schema([
                                ...static::customFormMainElements(),

                                ...static::optionalFormElements(),
                            ])
                            ->label(function (?array $state): string {
                                if ($state === null) {
                                    return mb_ucfirst(__('File Upload'));
                                }

                                return $state['title']['hu'] ?? mb_ucfirst(__('File Upload'));
                            }),
                        Forms\Components\Builder\Block::make('rich_editor')
                            ->icon('fas-file-word')
                            ->schema([
                                ...static::customFormMainElements(),

                                ...static::optionalFormElements(),
                            ])
                            ->label(function (?array $state): string {
                                if ($state === null) {
                                    return mb_ucfirst(__('Rich Editor'));
                                }

                                return $state['title']['hu'] ?? mb_ucfirst(__('Rich editor'));
                            }),
                        Forms\Components\Builder\Block::make('tags_input')
                            ->icon('fas-tags')
                            ->schema([
                                ...static::customFormMainElements(),

                                ...static::optionalFormElements(),
                            ])
                            ->label(function (?array $state): string {
                                if ($state === null) {
                                    return mb_ucfirst(__('Tags Input'));
                                }

                                return $state['title']['hu'] ?? mb_ucfirst(__('Tags Input'));
                            }),
                        Forms\Components\Builder\Block::make('text_area')
                            ->icon('fas-file-word')
                            ->schema([
                                ...static::customFormMainElements(),

                                ...static::optionalFormElements(),
                            ])
                            ->label(function (?array $state): string {
                                if ($state === null) {
                                    return mb_ucfirst(__('Text area'));
                                }

                                return $state['title']['hu'] ?? mb_ucfirst(__('Text area'));
                            }),
                        Forms\Components\Builder\Block::make('toggle_buttons')
                            ->schema([
                                ...static::customFormMainElements(),

                                ...static::optionalFormElements(),
                            ])
                            ->label(function (?array $state): string {
                                if ($state === null) {
                                    return mb_ucfirst(__('Toggle Button'));
                                }

                                return $state['title']['hu'] ?? mb_ucfirst(__('Toggle Button'));
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->description(fn($record): string => $record->description),
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListCustomForms::route('/'),
            'create' => Pages\CreateCustomForm::route('/create'),
            'edit' => Pages\EditCustomForm::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('menu.nav_group.content');
    }

    public static function getNavigationParentItem(): ?string
    {
        return __('resource.title.events');
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }
}
