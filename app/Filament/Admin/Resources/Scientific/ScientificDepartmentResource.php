<?php

namespace App\Filament\Admin\Resources\Scientific;

use App\Filament\Admin\Resources\Scientific\ScientificDepartmentResource\Pages;
use App\Models\Scientific\ScientificDepartment;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScientificDepartmentResource extends Resource
{
    protected static ?string $model = ScientificDepartment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('scientific_department');
    }

    public static function getPluralModelLabel(): string
    {
        return __('scientific_departments');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                SpatieMediaLibraryFileUpload::make('media')
                    ->hiddenLabel()
                    ->avatar()
                    ->collection('to-avatars')
                    ->alignCenter()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('name.hu')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name.en')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('media')
                    ->label('Logo')
                    ->collection('to-avatars')
                    ->wrap(),
                Tables\Columns\TextColumn::make('name')
                    ->listWithLineBreaks()
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
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
                Tables\Actions\EditAction::make(),
            ])
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScientificDepartments::route('/'),
            'create' => Pages\CreateScientificDepartment::route('/create'),
            'edit' => Pages\EditScientificDepartment::route('/{record}/edit'),
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
        return __('default');
    }

    public static function getNavigationSort(): ?int
    {
        return 0;
    }
}
