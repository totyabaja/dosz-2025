<?php

namespace App\Filament\Admin\Resources\Meeting;

use App\Filament\Admin\Resources\Meeting\MeetingResource\Pages;
use App\Filament\Admin\Resources\Meeting\MeetingResource\RelationManagers;
use App\Models\Meeting\Meeting;
use Filament\Forms;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MeetingResource extends Resource
{
    protected static ?string $model = Meeting::class;

    protected static ?string $navigationIcon = 'fas-people-arrows';

    public static function getRecordTitleAttribute(): ?string
    {
        return "name";
    }

    public static function getModelLabel(): string
    {
        return __('resource.title.meeting');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.title.meetings');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('name'),
                Infolists\Components\TextEntry::make('timepoint_start')
                    ->columnStart(1)
                    ->dateTime(),
                Infolists\Components\TextEntry::make('timepoint_end')
                    ->dateTime(),
                Infolists\Components\TextEntry::make('helye'),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('timepoint_start')
                    ->before('timepoint_start')
                    ->required(),
                Forms\Components\DateTimePicker::make('timepoint_end')
                    ->after('timepoint_start')
                    ->required(),
                Forms\Components\TextInput::make('helye')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('timepoint_start')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('timepoint_end')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('helye')
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
                Tables\Actions\ViewAction::make()
                    ->modal(),
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

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewMeeting::class,
            Pages\EditMeeting::class,
            Pages\ManageAgenda::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMeetings::route('/'),
            'create' => Pages\CreateMeeting::route('/create'),
            'view' => Pages\ViewMeeting::route('/{record}'),
            'edit' => Pages\EditMeeting::route('/{record}/edit'),
            'agenda' => Pages\ManageAgenda::route('/{record}/agenda'),
        ];
    }
}
