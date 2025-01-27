<?php

namespace App\Filament\ToAdmin\Resources;

use App\Filament\ToAdmin\Resources\ScientificDepartmentUserResource\Pages;
use App\Filament\ToAdmin\Resources\ScientificDepartmentUserResource\RelationManagers;
use App\Models\Scientific\ScientificDepartmentUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScientificDepartmentUserResource extends Resource
{
    protected static ?string $model = ScientificDepartmentUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('resource.title.name')
                    ->searchable()
                    ->searchable(['firstname', 'lastname']),
                Tables\Columns\IconColumn::make('accepted'),
            ])
            ->persistSearchInSession()
            ->persistColumnSearchesInSession()
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
            'index' => Pages\ListScientificDepartmentUsers::route('/'),
            'create' => Pages\CreateScientificDepartmentUser::route('/create'),
            'edit' => Pages\EditScientificDepartmentUser::route('/{record}/edit'),
        ];
    }
}
