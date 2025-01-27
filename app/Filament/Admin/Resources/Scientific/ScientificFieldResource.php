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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Repeater::make('subfields')
                    ->relationship('subfields')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required()
                            ->label('Subfield Name')
                            ->distinct(),
                    ])
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
        return 0;
    }
}
