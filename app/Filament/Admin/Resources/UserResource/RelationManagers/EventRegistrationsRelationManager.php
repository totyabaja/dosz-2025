<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use App\Filament\Event\Resources\EventRegistrationResource;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventRegistrationsRelationManager extends RelationManager
{
    protected static string $relationship = 'event_registrations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('event.name.hu')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('event.name.hu')
            ->columns([
                Tables\Columns\TextColumn::make('event.name')
                    ->listWithLineBreaks(),
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
