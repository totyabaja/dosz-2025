<?php

namespace App\Filament\ToAdmin\Resources\Blog;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Admin\Resources\Blog\PostResource as AdminPostResource;
use App\Filament\ToAdmin\Resources\Blog\PostResource\Pages;
use App\Filament\ToAdmin\Resources\Blog\PostResource\RelationManagers;
use App\Models\Blog\Post;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('scientific_department_id')
                    ->required(),
                Forms\Components\Section::make('Image')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('filament_avatar_url')
                            ->hiddenLabel()
                            ->disk('public')
                            ->collection('post-banners')
                            ->alignCenter()
                            ->columnSpanFull()
                            ->multiple(),
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

                        Forms\Components\Toggle::make('is_featured')
                            ->required(),

                        Forms\Components\Tabs::make()->schema([
                            Forms\Components\Tabs\Tab::make('Magyar')->schema([
                                Forms\Components\TextInput::make('short_description.hu')
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
                                Forms\Components\TextInput::make('short_description.en')
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
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('media')->label('Image')
                    ->collection('post-images')
                    ->wrap(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->listWithLineBreaks()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('author.name')
                    ->searchable(['firstname', 'lastname'])
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->getStateUsing(fn(Post $record): string => $record->published_at?->isPast() ? 'Published' : 'Draft')
                    ->colors([
                        'success' => 'Published',
                    ]),

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
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->where('scientific_department_id', auth()->user()->currentDepartment()->id);
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
}
