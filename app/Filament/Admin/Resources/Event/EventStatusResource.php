<?php

namespace App\Filament\Admin\Resources\Event;

use App\Filament\Admin\Resources\Event\EventStatusResource\Pages;
use App\Filament\Admin\Resources\Event\EventStatusResource\RelationManagers;
use App\Models\Event\EventStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventStatusResource extends Resource
{
    protected static ?string $model = EventStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('resource.title.event_status');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.title.event_statuses');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name.hu')
                    ->required(),
                Forms\Components\TextInput::make('name.en')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->listWithLineBreaks(),
            ])
            ->defaultSort('id')
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
            'index' => Pages\ListEventStatuses::route('/'),
            'create' => Pages\CreateEventStatus::route('/create'),
            'edit' => Pages\EditEventStatus::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('menu.nav_group.content');
    }

    public static function getNavigationParentItem(): ?string
    {
        return __('resource.title.events');
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }
}
