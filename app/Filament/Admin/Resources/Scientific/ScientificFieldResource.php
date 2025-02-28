<?php

namespace App\Filament\Admin\Resources\Scientific;

use App\Filament\Admin\Resources\Scientific\ScientificFieldResource\Pages;
use App\Filament\Admin\Resources\Scientific\ScientificFieldResource\RelationManagers;
use App\Models\Scientific\ScientificField;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScientificFieldResource extends Resource
{
    protected static ?string $model = ScientificField::class;

    protected static ?string $navigationIcon = 'fas-gears';

    public static function getRecordTitleAttribute(): ?string
    {
        return 'name';
    }

    public static function getModelLabel(): string
    {
        return __('resource.components.scientific_field');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.components.scientific_fields');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(1),
                Forms\Components\Repeater::make('subfields')
                    ->relationship('subfields')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required()
                            ->label('Subfield Name')
                            ->distinct(),
                    ])
                    ->columnSpan(2)
                    ->collapsible(), // Összecsukható a jobb átláthatóság érdekében
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
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
            'index' => Pages\ListScientificFields::route('/'),
            'create' => Pages\CreateScientificField::route('/create'),
            'edit' => Pages\EditScientificField::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('menu.nav_group.settings');
    }

    public static function getNavigationSort(): ?int
    {
        return 4;
    }
}
