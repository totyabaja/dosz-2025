<?php

namespace App\Filament\Admin\Resources\Blog;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Admin\Resources\Blog\PostResource\Pages;
use App\Models\Blog\Post;
use App\Models\Scientific\ScientificDepartment;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $slug = 'blog/posts';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'fluentui-news-20';

    public static function getModelLabel(): string
    {
        return __('resource.title.post');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.title.posts');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Image')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('filament_avatar_url')
                            ->hiddenLabel()
                            ->disk('public')
                            ->collection('post-banners')
                            ->alignCenter()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name.hu')
                            ->required(fn($get): bool => $get('name.en') == '' || ($get('name.hu') != '' && $get('name.en') != ''))
                            ->live(debounce: 500)
                            ->maxLength(255)
                            ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                        Forms\Components\TextInput::make('name.en')
                            ->required(fn($get): bool => $get('name.hu') == '')
                            ->live(debounce: 500)
                            ->maxLength(255)
                            ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set, Forms\Get $get) => $operation === 'create' ? ($get('name.hu') == '' ? $set('slug', Str::slug($state)) : null) : null),

                        Forms\Components\TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(255)
                            ->unique(Post::class, 'slug', ignoreRecord: true),

                        Forms\Components\Tabs::make()->schema([
                            Forms\Components\Tabs\Tab::make('Magyar')->schema([
                                TextInput::make('short_description.hu')
                                    ->label('Short Description (HU)')
                                    ->columnSpan('full')
                                    ->required(fn($get): bool => $get('name.hu') != ''),
                                TinyEditor::make('description.hu')
                                    ->label('Description (HU)')
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsVisibility('public')
                                    ->fileAttachmentsDirectory('uploads') // TODO
                                    ->profile('default')
                                    ->columnSpan('full')
                                    ->required(fn($get): bool => $get('name.hu') != ''),
                            ]),
                            Forms\Components\Tabs\Tab::make('Angol')->schema([
                                TextInput::make('short_description.en')
                                    ->label('Short Description (EN)')
                                    ->columnSpan('full')
                                    ->required(fn($get): bool => $get('name.en') != ''),
                                TinyEditor::make('description.en')
                                    ->label('Description (EN)')
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsVisibility('public')
                                    ->fileAttachmentsDirectory('uploads') // TODO
                                    ->profile('default')
                                    ->columnSpan('full')
                                    ->required(fn($get): bool => $get('name.en') != ''),
                            ]),

                        ])->columnSpanFull(),

                        Forms\Components\Select::make('blog_author_id')
                            ->relationship(
                                name: 'author',
                                // modifyQueryUsing: fn(Builder $query) => $query->with('roles')->whereRelation('roles', 'name', '=', 'admin'),
                                modifyQueryUsing: fn(Builder $query) => $query->with('roles') //->whereRelation('roles', 'name', 'in', '("super_admin", "dosz_admin")'),
                            )
                            ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->name)
                            ->searchable(['lastname', 'firstname'])
                            ->preload()
                            ->required(),


                        Forms\Components\DatePicker::make('published_at')
                            ->label('Published Date'),

                        SpatieTagsInput::make('tags')
                            ->splitKeys(['Tab', ',']),

                        Forms\Components\Select::make('scientific_department_id')
                            ->relationship('scientific_department')
                            ->options(
                                fn() =>
                                ScientificDepartment::all()
                                    ->sortBy('filament_name')
                                    ->mapWithKeys(fn($item) => [$item->id => $item->filament_name])
                            )
                            ->native(false)
                            ->searchable()
                            ->preload(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Dokumentumok')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('media')
                            ->hiddenLabel()
                            ->disk('public')
                            ->collection('post-documents')
                            ->alignCenter()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('filament_avatar_url')
                    ->label('Image')
                    ->disk('public')
                    ->collection('post-banners')
                    ->wrap(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->listWithLineBreaks()
                    ->sortable(),

                Tables\Columns\IconColumn::make('scientific_department_id'),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('author.name')
                    ->searchable(['firstname', 'lastname'])
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published Date'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\TernaryFilter::make('scientific_department_posts')
                    ->nullable()
                    ->default(true)
                    ->attribute('scientific_department_id')
                    ->placeholder('All posts')
                    ->trueLabel('Csak DOSz hírek')
                    ->falseLabel('Minden hír')
                    ->queries(
                        true: fn(Builder $query) => $query->whereNull('scientific_department_id'),
                        false: fn(Builder $query) => $query->whereNotNull('scientific_department_id'),
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->hiddenLabel()->tooltip('Detail'),
                Tables\Actions\EditAction::make()->hiddenLabel()->tooltip('Edit'),
                Tables\Actions\DeleteAction::make()->hiddenLabel()->tooltip('Delete'),
                Tables\Actions\RestoreAction::make()->hiddenLabel()->tooltip('Delete'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->whereNull('scientific_department_id');
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
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
