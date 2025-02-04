<?php

namespace App\Filament\Admin\Resources\Menu\PublicMenuResource\RelationManagers;

use App\Filament\Admin\Resources\Menu\PublicMenuResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChildrenRelationManager extends RelationManager
{
    protected static string $relationship = 'children';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('parent_id')
                    ->default(fn($get, $set, $livewire) => $livewire->ownerRecord->id),
                ...PublicMenuResource::menuItemSchema(),
            ]);
        //PublicMenuResource::form($form);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label.hu')
            ->columns([
                Tables\Columns\TextColumn::make('label.hu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('parent.label.hu'),
            ])
            ->reorderable(column: 'order')
            ->defaultSort('order')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->recordUrl(fn($record) => PublicMenuResource::getUrl('edit', ['record' => $record]))
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
