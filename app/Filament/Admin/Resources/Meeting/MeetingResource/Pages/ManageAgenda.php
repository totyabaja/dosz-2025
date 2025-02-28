<?php

namespace App\Filament\Admin\Resources\Meeting\MeetingResource\Pages;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Admin\Resources\Meeting\MeetingResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class ManageAgenda extends ManageRelatedRecords
{
    protected static string $resource = MeetingResource::class;

    protected static string $relationship = 'agendas';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Agendas';
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('name')
                    ->label('name'),
                Infolists\Components\ViewEntry::make('description')
                    ->label('Leírás')
                    ->view('filament.custom_layouts.tinyeditor_description_view')
                    ->columnSpanFull(),
                Infolists\Components\TextEntry::make('users.firstname')
                    ->label('Felelősök, előterjesztők')
                    ->bulleted(),
                // TODO: a fájlok is elérhetőek legyenek
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TinyEditor::make('description')
                    ->label('Leírás')
                    ->nullable()
                    ->columnSpanFull(),
                Forms\Components\Select::make('users')
                    ->label('Felelősök, előterjesztők')
                    ->multiple()
                    ->relationship('users', 'name')
                    ->options(fn() => \App\Models\User::query()
                        ->whereHas('positions.position_subtype', function ($query) {
                            $query->where('position_type_id', 1);
                        })
                        ->get()
                        ->mapWithKeys(fn($item) => [$item->id => $item->name])
                        ->toArray())
                    ->searchable()
                    ->preload(),
                SpatieMediaLibraryFileUpload::make('media')
                    ->label('Dokumentumok')
                    ->disk('public')
                    ->collection('agenda-documents')
                    ->downloadable()
                    ->alignCenter(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('order'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
            ])
            ->reorderable(column: 'order')
            ->defaultSort('order')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                //Tables\Actions\AssociateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                //Tables\Actions\DissociateAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DissociateBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
