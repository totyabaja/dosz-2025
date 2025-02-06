<?php

namespace App\Filament\Admin\Resources\Scientific;

use App\Filament\Admin\Resources\Scientific\UniversityResource\Pages;
use App\Filament\Admin\Resources\Scientific\UniversityResource\Pages\CreateUniversity;
use App\Filament\Admin\Resources\Scientific\UniversityResource\Pages\EditUniversity;
use App\Filament\Admin\Resources\Scientific\UniversityResource\Pages\ViewUniversity;
use App\Filament\Admin\Resources\Scientific\UniversityResource\RelationManagers;
use App\Filament\Admin\Resources\Scientific\UniversityResource\RelationManagers\DoctoralSchoolsRelationManager;
use App\Models\Scientific\University;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UniversityResource extends Resource
{
    protected static ?string $model = University::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getRecordTitleAttribute(): ?string
    {
        return 'short_name';
    }

    public static function getModelLabel(): string
    {
        return __('resource.title.university');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.title.universities');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                SpatieMediaLibraryFileUpload::make('media')
                    ->hiddenLabel()
                    ->avatar()
                    ->collection('university-avatars')
                    ->alignCenter()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('full_name.hu')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('full_name.en')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('short_name')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('url')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('intezmenyi_szabalyzat')
                    ->required()
                    ->maxLength(500),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('media')
                    ->label('Logo')
                    ->collection('university-avatars')
                    ->wrap(),
                Tables\Columns\TextColumn::make(mb_ucfirst(__('reg.fieldset.full_name')))
                    ->listWithLineBreaks()
                    ->searchable(),
                Tables\Columns\TextColumn::make('short_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->recordUrl(fn($record) => UniversityResource::getUrl('view', ['record' => $record]))
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getRecordSubNavigation($page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewUniversity::class,
            Pages\EditUniversity::class,
            Pages\ManageDoctoralSchools::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUniversities::route('/'),
            'create' => Pages\CreateUniversity::route('/create'),
            'view' => Pages\ViewUniversity::route('/{record}'),
            'edit' => Pages\EditUniversity::route('/{record}/edit'),
            'doctoral-schools' => Pages\ManageDoctoralSchools::route('/{record}/doctoral-shools')
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
        return __('menu.nav_group.settings');
    }

    public static function getNavigationSort(): ?int
    {
        return 0;
    }
}
