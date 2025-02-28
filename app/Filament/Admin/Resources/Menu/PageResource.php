<?php

namespace App\Filament\Admin\Resources\Menu;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Admin\Resources\Menu\PageResource\Pages;
use App\Models\Menu\Page;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Wiebenieuwenhuis\FilamentCodeEditor\Components\CodeEditor;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('resource.title.page');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.title.pages');
    }

    public static function form(Form $form): Form
    {
        $livewire_components = collect(File::allFiles(app_path('Livewire/PublicPagesExtra')))
            ->mapWithKeys(fn($file) => [
                'App\\Livewire\\PublicPagesExtra\\' . $file->getFilenameWithoutExtension() => $file->getFilenameWithoutExtension(),
            ])
            ->toArray();

        return $form
            ->schema([
                Forms\Components\TextInput::make('slug')
                    ->label(mb_ucfirst(__('resource.components.slug')))
                    ->required()
                    ->unique(Page::class, 'slug', ignoreRecord: true)
                    ->readOnlyOn('edit')
                    ->maxLength(255),

                Section::make()->schema([
                    Forms\Components\TextInput::make('name.hu')
                        ->label(mb_ucfirst(__('resource.components.name_hungarian')))
                        ->required(fn($get): bool => $get('name.en') == '' || ($get('name.hu') != '' && $get('name.en') != ''))
                        ->afterStateUpdated(fn(?string $state, $set) => $set('slug', Str::slug($state)))
                        ->live(debounce: 500),
                    Forms\Components\TextInput::make('name.en')
                        ->label(mb_ucfirst(__('resource.components.name_english')))
                        ->required(fn($get): bool => $get('name.hu') == '')
                        ->live(debounce: 500),
                ])->columns(2),

                Forms\Components\Tabs::make()->schema([
                    Forms\Components\Tabs\Tab::make(mb_ucfirst(__('resource.tabs.hungarian')))->schema([
                        TinyEditor::make('description.hu')
                            ->label(mb_ucfirst(__('resource.components.description_hungarian')))
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsVisibility('public')
                            ->fileAttachmentsDirectory('uploads') // TODO
                            ->profile('default')
                            ->columnSpan('full'),
                        //->required(fn ($get): bool => $get('name.hu') != ''),
                    ]),
                    Forms\Components\Tabs\Tab::make(mb_ucfirst(__('resource.tabs.english')))->schema([
                        TinyEditor::make('description.en')
                            ->label(mb_ucfirst(__('resource.components.description_english')))
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsVisibility('public')
                            ->fileAttachmentsDirectory('uploads') // TODO
                            ->profile('default')
                            ->columnSpan('full'),
                        //->required(fn($get): bool => $get('name.en') != ''),
                    ]),

                ])->columnSpanFull(),

                Section::make()->schema([
                    Forms\Components\Select::make('livewire_component_top')
                        ->label(mb_ucfirst(__('resource.components.livewire_component_top')))
                        ->options($livewire_components)
                        ->nullable()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('component_name')
                                ->label(__('Component Name (kebab-case)'))
                                ->required(),
                        ])
                        ->editOptionForm([
                            Forms\Components\TextInput::make('component_name')
                                ->label(__('Component Name (kebab-case)'))
                                ->required()
                                ->readOnly(),
                            CodeEditor::make('component_content')
                                ->required(),
                            CodeEditor::make('component_view_content')
                                ->required(),

                        ])
                        ->fillEditOptionActionFormUsing(function ($state) {
                            $component_class = Str::afterLast($state, '\\');
                            $component_path = app_path("Livewire/PublicPagesExtra/{$component_class}.php");
                            $view_path = resource_path('views/livewire/public-pages-extra/' . \Illuminate\Support\Str::kebab($component_class) . '.blade.php');

                            return [
                                'component_name' => Str::kebab($component_class),
                                'component_content' => file_exists($component_path) ? file_get_contents($component_path) : '',
                                'component_view_content' => file_exists($view_path) ? file_get_contents($view_path) : '',
                            ];
                        })
                        ->createOptionUsing(function ($data) {
                            $name = Str::studly(Str::replace('-', ' ', $data['component_name']));
                            $class_path = app_path("Livewire/PublicPagesExtra/{$name}.php");
                            $view_path = resource_path('views/livewire/public-pages-extra/' . Str::kebab($name) . '.blade.php');

                            if (File::exists($class_path)) {
                                throw ValidationException::withMessages(['component_name' => __('This component already exists.')]);
                            }

                            // Generáljuk a Livewire osztályt
                            $class_content = <<<PHP
                                <?php

                                namespace App\Livewire\PublicPagesExtra;

                                use Livewire\Component;

                                class {$name} extends Component
                                {
                                    public function render()
                                    {
                                        return view('livewire.public-pages-extra.{$data['component_name']}');
                                    }
                                }
                                PHP;

                            File::put($class_path, $class_content);

                            // Generáljuk a Livewire nézet fájlt
                            $view_content = <<<'HTML'
                                <div>
                                    //
                                </div>
                                HTML;

                            File::put($view_path, $view_content);
                        })
                        ->updateOptionUsing(function ($data) {
                            $name = Str::studly(Str::replace('-', ' ', $data['component_name']));
                            $class_path = app_path("Livewire/PublicPagesExtra/{$name}.php");
                            $view_path = resource_path('views/livewire/public-pages-extra/' . Str::kebab($name) . '.blade.php');

                            File::put($class_path, $data['component_content']);
                            File::put($view_path, $data['component_view_content']);
                        }),
                    Forms\Components\Select::make('livewire_component_bottom')
                        ->label(mb_ucfirst(__('resource.components.livewire_component_bottom')))
                        ->options($livewire_components)
                        ->nullable(),
                ])->columnSpanFull()
                    ->columns(2),

                Forms\Components\TextInput::make('version')
                    ->label(mb_ucfirst(__('resource.components.version')))
                    //->required()
                    ->numeric()
                    ->disabled()
                    ->default(1)
                    ->hiddenOn(['create', 'edit']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slug')
                    ->label(mb_ucfirst(__('resource.components.slug')))
                    ->searchable(),
                Tables\Columns\TextColumn::make('version')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('menu.nav_group.settings');
    }

    public static function getNavigationParentItem(): ?string
    {
        return __('resource.title.menus');
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public function createLivewireForm(): array
    {
        return [];
    }
}
