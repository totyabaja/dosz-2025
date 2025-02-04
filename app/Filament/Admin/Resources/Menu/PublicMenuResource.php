<?php

namespace App\Filament\Admin\Resources\Menu;

use App\Filament\Admin\Resources\Menu\PublicMenuResource\Pages;
use App\Filament\Admin\Resources\Menu\PublicMenuResource\RelationManagers;
use App\Filament\Admin\Resources\Menu\PublicMenuResource\RelationManagers\ChildrenRelationManager;
use App\Models\Menu\Page;
use App\Models\Menu\PublicMenu;
use App\Models\Menu\PublicMenuPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PublicMenuResource extends Resource
{
    protected static ?string $model = PublicMenu::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('resource.title.menu');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.title.menus');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ...static::menuItemSchema(),
            ]);
    }

    public static function menuItemSchema(): array
    {
        return [
            Forms\Components\Grid::make([
                'default' => 1,
                'md' => 2,
            ])->schema([
                Forms\Components\TextInput::make('custom_id')
                    ->maxLength(255),
                Forms\Components\TextInput::make('label.hu')
                    ->required()
                    ->columnStart(1),
                Forms\Components\TextInput::make('label.en')
                    ->required(),
                Forms\Components\Select::make('link_type')
                    ->options([
                        null => 'Semmire',
                        'slug' => 'Link',
                        'external_url' => 'Külső link',
                        'page' => "Oldalra",
                    ])
                    ->native(false)
                    ->live()
                    ->afterStateHydrated(function ($record, $set) {
                        if ($record->slug ?? False)
                            $set('link_type', 'slug');
                        elseif ($record->external_url ?? False)
                            $set('link_type', 'external_url');
                        elseif ($record->menu_page ?? False)
                            $set('link_type', 'page');
                    })
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state == null) {
                            $set('target', '_self');
                            $set('slug', null);
                            $set('external_url', null);
                            $set('page', null);
                        }
                    }),
                Forms\components\Fieldset::make()
                    ->label('Link, amire mutat')
                    ->hidden(fn($get) => !$get('link_type'))
                    ->schema([
                        Forms\Components\Select::make('target')
                            ->options([
                                '_blank' => '_blank',
                                '_self' => '_self',
                            ])
                            ->default('_self'),
                        Forms\Components\TextInput::make('slug')
                            ->maxLength(255)
                            ->prefix(env('APP_URL') . '/')
                            ->visible(fn($get) => $get('link_type') === 'slug')
                            ->required(fn($get) => $get('link_type') === 'slug'),
                        Forms\Components\TextInput::make('external_url')
                            ->maxLength(255)
                            ->visible(fn($get) => $get('link_type') === 'external_url')
                            ->required(fn($get) => $get('link_type') === 'external_url'),

                        Forms\Components\Select::make('menu_page.page')
                            ->relationship('menu_page')
                            ->options(fn() => Page::all()->mapWithKeys(fn($item) => [$item->id => $item->name['hu']]))
                            ->preload()
                            ->visible(fn($get) => $get('link_type') === 'page')
                            ->required(fn($get) => $get('link_type') === 'page')
                            ->native(false)
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $page = Page::find($state);

                                $set('label.hu', $page->name['hu']);
                                $set('label.en', $page->name['en']);
                            })
                            ->createOptionForm(fn($form) => PageResource::form($form))
                            ->editOptionForm(fn($form) => PageResource::form($form)),

                    ]),
            ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('custom_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('label.hu')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cel')
                    ->label('Cél')
                    ->getStateUsing(function ($record) {
                        if ($record->slug)
                            return $record->slug;
                        elseif ($record->external_url)
                            return $record->external_url;
                        elseif ($record->target)
                            return $record->target;
                        else "-";
                    }),
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
                Tables\Filters\TernaryFilter::make('parent_filter')
                    ->nullable()
                    ->default(true)
                    ->attribute('parent_id')
                    ->placeholder('All users')
                    ->trueLabel('Szülő menü elem')
                    ->falseLabel('Alárendelt elem')
                    ->queries(
                        true: fn(Builder $query) => $query->whereNull('parent_id'),
                        false: fn(Builder $query) => $query->whereNotNull('parent_id'),
                    )
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
            ChildrenRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPublicMenus::route('/'),
            'create' => Pages\CreatePublicMenu::route('/create'),
            'edit' => Pages\EditPublicMenu::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('menu.nav_group.content');
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }
}
