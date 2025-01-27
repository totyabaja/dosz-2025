<?php

namespace App\Filament\Admin\Resources\Position;

use App\Filament\Admin\Resources\Position\PositionTypeResource\Pages;
use App\Models\Position\PositionType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PositionTypeResource extends Resource
{
    protected static ?string $model = PositionType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('position type');
    }

    public static function getPluralModelLabel(): string
    {
        return __('position types');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Section::make()->schema([
                    Forms\Components\Repeater::make('subtypes') // Kapcsolódó PositionSubtype
                        ->relationship('subtypes') // Ezt a modellben kell definiálni
                        ->schema([
                            Forms\Components\TextInput::make('name.hu')
                                ->label('Name (HU)')
                                ->required(),
                            Forms\Components\TextInput::make('name.en')
                                ->label('Name (EN)')
                                ->required(),
                        ])
                        ->columns(2)
                        ->orderColumn('order')
                        ->reorderableWithDragAndDrop(true) // Húzogatás támogatása a sorrend változtatásához
                        ->collapsible()
                        ->collapsed(true)
                        ->live()
                        ->itemLabel(fn(array $state): ?string => $state['name']['hu'] ?? null)
                        ->columnSpanFull(),
                ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subtypes_count')
                    ->counts('subtypes')
                    ->badge(),
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
            'index' => Pages\ListPositionTypes::route('/'),
            'create' => Pages\CreatePositionType::route('/create'),
            'edit' => Pages\EditPositionType::route('/{record}/edit'),
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
