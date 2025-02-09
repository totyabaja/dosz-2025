<?php

namespace App\Filament\ToAdmin\Resources;

use App\Filament\Admin\Resources\UserResource;
use App\Filament\ToAdmin\Resources\ScientificDepartmentUserResource\Pages;
use App\Filament\ToAdmin\Resources\ScientificDepartmentUserResource\RelationManagers;
use App\Models\Scientific\ScientificDepartmentUser;
use Filament\Infolists;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScientificDepartmentUserResource extends Resource
{
    protected static ?string $model = ScientificDepartmentUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(mb_ucfirst(__('reg.fieldset.full_name')))
                    ->searchable()
                    ->searchable(['firstname', 'lastname']),
                Tables\Columns\TextColumn::make('acceptance_datetime')
                    ->label(mb_ucfirst(__('resource.components.acceptance_datetime')))
                    ->date()
                    ->since(),
            ])
            ->recordUrl(null)
            ->persistSearchInSession()
            ->persistColumnSearchesInSession()
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('showUserInfos')
                    ->label('Felhasználó adatai')
                    ->infolist([
                        Infolists\Components\Section::make()
                            ->relationship('user')
                            ->schema([

                                Infolists\Components\Fieldset::make(mb_ucfirst(__('reg.fieldset.full_name')))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('firstname')
                                            ->label(__('Firstname')),

                                        Infolists\Components\TextEntry::make('lastname')
                                            ->label(__('Lastname')),
                                    ]),

                                Infolists\Components\TextEntry::make('email')
                                    ->label(__('Email'))
                                    ->copyable(),

                                Infolists\Components\TextEntry::make('roles.name')
                                    ->label(__('Roles'))
                                    ->badge(),

                                Infolists\Components\TextEntry::make('scientific_departments.name')
                                    ->label(__('Scientific Departments'))
                                    ->formatStateUsing(fn($state) => $state[session()->get('locale', 'hu')])
                                    ->badge(),

                            ])
                    ])
                    ->slideOver()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->where('scientific_department_id', auth()->user()->currentDepartment()->id)
            ->where('accepted', true);
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
            'index' => Pages\ListScientificDepartmentUsers::route('/'),
        ];
    }
}
