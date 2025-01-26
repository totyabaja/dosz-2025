<?php

namespace App\Filament\Admin\Resources\Aid;

use App\Filament\Admin\Resources\Aid\GeneralQuestionResource\Pages;
use App\Filament\Admin\Resources\Aid\GeneralQuestionResource\RelationManagers;
use App\Models\Aid\GeneralQuestion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GeneralQuestionResource extends Resource
{
    protected static ?string $model = GeneralQuestion::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('resource.title.alt_ker');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.title.alt_kers');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('question')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('answer')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('language')
                    ->required()
                    ->options(function (): array {
                        $array = (\BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch::make())->getLocales();

                        return array_combine($array, $array);
                    })
                    ->default('hu'),
                Forms\Components\Toggle::make('is_visible')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->searchable(),
                Tables\Columns\TextColumn::make('language')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_visible')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListGeneralQuestions::route('/'),
            'create' => Pages\CreateGeneralQuestion::route('/create'),
            'edit' => Pages\EditGeneralQuestion::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationGroup(): ?string
    {
        return __('resource.title.legal_aid');
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }
}
