<?php

namespace App\Filament\Admin\Resources\Event\EventResource\Pages;

use App\Filament\Admin\Resources\Event\EventResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageEventForms extends ManageRelatedRecords
{
    protected static string $resource = EventResource::class;

    protected static string $relationship = 'event_custom_forms';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Event Custom Forms';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('custom_form_id')
                    ->relationship('custom_form', 'name')
                    // TODO: nem szűri ki
                    //->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options([
                        'reg' => 'Regisztrációs form',
                        'feedback' => 'Visszajelzés form',
                    ])
                    ->native(false)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('custom_form.name')
            ->columns([
                Tables\Columns\TextColumn::make('custom_form.name'),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
