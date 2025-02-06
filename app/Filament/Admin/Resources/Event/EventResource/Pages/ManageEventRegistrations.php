<?php

namespace App\Filament\Admin\Resources\Event\EventResource\Pages;

use App\Filament\Admin\Resources\Event\EventResource;
use App\Filament\Admin\Resources\UserResource;
use App\Filament\Components\Event\CustomFormInfolist;
use App\Filament\Components\Event\PublicationForm;
use App\Filament\Components\Event\PublicationInfolist;
use App\Filament\Components\Event;
use App\Filament\Event\Resources\EventRegistrationResource;
use Filament\Actions;
use Filament\Infolists;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class ManageEventRegistrations extends ManageRelatedRecords
{
    protected static string $resource = EventResource::class;

    protected static string $relationship = 'event_registrations';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationLabel(): string
    {
        return 'Event Registrations';
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Tabs::make()
                    ->schema(fn($record) => [
                        Infolists\Components\Tabs\Tab::make('Számlázási adatok')
                            ->schema([
                                ...Event\UserInfolist::schema(),
                                ...Event\BillingInfolist::schema(),
                            ]),

                        ...EventRegistrationResource::publicationNecessaryView($record),
                        ...EventRegistrationResource::extraFormView($record),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(fn($record) => [
                Forms\Components\Wizard::make()
                    ->skippable()
                    //->afterStateHydrated(fn($set) => static::userInfos($set))
                    ->persistStepInQueryString('reg-event')
                    ->schema([
                        Forms\Components\Wizard\Step::make('presonl-infos')
                            ->label('Személyes adatok')
                            ->icon('heroicon-o-user')
                            ->schema([
                                ...Event\UserForm::schema(),
                            ]),
                        Forms\Components\Wizard\Step::make('Számlázási adatok')
                            ->schema([
                                ...Event\BillingForm::schema(),
                            ]),
                        ...EventRegistrationResource::publicationNecessaryForm($record),
                        ...EventRegistrationResource::extraForm($record),
                        Forms\Components\Wizard\Step::make('Extra')
                            ->schema([
                                Forms\Components\Toggle::make('adatkezelesi')
                                    ->label('Megismertem az Adatkezelési Tájékoztatót és a Rendezvény Adatkezelési Tájékoztató Kivonatát.')
                                    ->onIcon('heroicon-m-document-check')
                                    ->offIcon('heroicon-m-exclamation-triangle')
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->afterStateHydrated(function ($state, $set, $record) {
                                        if ($record && !is_null($record->accepted_data_protection)) {
                                            $set(
                                                'adatkezelesi',
                                                true
                                            );
                                        }
                                    })
                                    ->accepted(),
                                Forms\Components\Toggle::make('hozzajarulas')
                                    ->label('Ezúton hozzájárulok, hogy a Doktoranduszok Országos Szövetsége, mint adatkezelő egyéni vagy nem tömeges (nem tömegről készült) fénykép vagy videófelvételeket készítsen a Rendezvény dokumentálása és jövőbeni népszerűsítése céljából. Hozzájárulás hiányában az Adatkezelő nem készíthet rólam egyéni vagy nem tömeges (tömegről készült) fénykép vagy videófelvételeket.')
                                    ->onIcon('heroicon-m-document-check')
                                    ->offIcon('heroicon-m-exclamation-triangle')
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->afterStateHydrated(function ($state, $set, $record) {
                                        if ($record && !is_null($record->accepted_data_use)) {
                                            $set(
                                                'hozzajarulas',
                                                true
                                            );
                                        }
                                    }),
                            ]),
                    ])
                    ->submitAction(new HtmlString(Blade::render(
                        <<<'BLADE'
                            <x-filament::button
                                type="submit"
                                size="sm"
                            >
                                Submit
                            </x-filament::button>
                        BLADE
                    )))
                    ->columnSpanFull()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('status.name'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(), // TODO
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
