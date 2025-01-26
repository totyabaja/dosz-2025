<?php

namespace App\Filament\Admin\Resources\Aid;

use App\Filament\Admin\Resources\Aid\LegalAidResource\Pages;
use App\Models\Aid\LegalAid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LegalAidResource extends Resource
{
    protected static ?string $model = LegalAid::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('resource.title.legal_aid');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.title.legal_aids');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('first_name'),
                TextEntry::make('last_name'),
                TextEntry::make('email')
                    ->copyable()
                    ->copyMessage('Copied!')
                    ->copyMessageDuration(1500),
                TextEntry::make('university.full_name'),
                TextEntry::make('doctoral_school.full_name'),
                TextEntry::make('question')
                    ->columnSpanFull(),
                Actions::make([
                    Action::make('modalJog')
                        ->form(fn($record) => [
                            Placeholder::make('question')
                                ->content(fn($record): string => $record->question)
                                ->columnSpanFull(),

                            RichEditor::make('válaszom'),
                        ])
                        ->modalHeading(__('Jogsegély kérdés megválaszolása')),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('university.short_name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('doctoral_school.full_name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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
            'index' => Pages\ListLegalAids::route('/'),
            // 'create' => Pages\CreateLegalAid::route('/create'),
            // 'edit' => Pages\EditLegalAid::route('/{record}/edit'),
            'view' => Pages\ViewLegalAid::route('/{record}'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('resource.title.legal_aid');
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }
}
