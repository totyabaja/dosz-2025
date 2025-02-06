<?php

namespace App\Filament\Event\Resources;

use App\Filament\Event\Resources\EventRegistrationResource\Pages;
use App\Filament\Event\Resources\EventRegistrationResource\RelationManagers;
use App\Models\Event\EventRegistration;
use Filament\Infolists;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use App\Filament\Components\Event;
use App\Models\Position\Position;
use CodeWithDennis\SimpleAlert\Components\Forms\SimpleAlert;
use CodeWithDennis\SimpleAlert\Components\Infolists\SimpleAlert as InfolistsSimpleAlert;
use Filament\Forms\Get;
use Filament\Infolists\Infolist;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EventRegistrationResource extends Resource
{
    protected static ?string $model = EventRegistration::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id())
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Tabs::make()
                    ->schema(fn($record) => [

                        Infolists\Components\Tabs\Tab::make('Számlázási adatok')
                            ->schema([
                                ...Event\UserInfolist::schema(),
                                InfolistsSimpleAlert::make('billing-info')
                                    ->danger()
                                    ->description(new HtmlString(
                                        'A számlázási adatok helyességéért te felelsz. <b>A helytelen adatok megadása miatti számlázási hibákért nem tudunk felelősséget vállalni. A jelentkezési határidő lezárultát követően nincs lehetőség a számlázási adatok módosítására.</b>'
                                    )),
                                ...Event\BillingInfolist::schema(),
                                Infolists\Components\TextEntry::make('accepted_data_protection')
                                    ->hiddenLabel()
                                    ->formatStateUsing(fn() => '.....')
                                    ->iconPosition('before')
                                    ->icon(fn($state) => $state ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle')
                                    ->iconColor(fn($state) => $state ? 'success' : 'danger'),

                                Infolists\Components\TextEntry::make('accepted_data_use')
                                    ->hiddenLabel()
                                    ->formatStateUsing(fn() => '.....')
                                    ->icon(fn($state) => $state ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle')
                                    ->color(fn($state) => $state ? 'success' : 'danger'),

                            ]),
                        ...static::publicationNecessaryView($record),
                        ...static::extraFormView($record),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function form(Form $form): Form
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
                                SimpleAlert::make('billing-info')
                                    ->danger()
                                    ->description(new HtmlString(
                                        'A számlázási adatok helyességéért te felelsz. <b>A helytelen adatok megadása miatti számlázási hibákért nem tudunk felelősséget vállalni. A jelentkezési határidő lezárultát követően nincs lehetőség a számlázási adatok módosítására.</b>'
                                    )),
                                ...Event\BillingForm::schema(),
                            ]),
                        ...static::publicationNecessaryForm($record),
                        ...static::extraForm($record),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.name')
                    ->listWithLineBreaks()
                    // TODO: search beállítása
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
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'reviewing' => 'Reviewing',
                        'published' => 'Published',
                    ]),
                Tables\Filters\Filter::make('opened')
                    ->query(fn(Builder $query): Builder => $query)
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn($record): bool => $record->event->event_registration_editable),
                Tables\actions\DeleteAction::make()
                    ->visible(fn($record): bool => $record->event->event_registration_editable),
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
            // .
        ];
    }

    public static function publicationNecessaryView(EventRegistration $record): array
    {
        return $record->event->abstract_neccessary ?? False
            ? [Infolists\Components\Tabs\Tab::make('Publikációk')
                ->schema([
                    ...Event\PublicationInfolist::schema(),
                ])]
            : [];
    }

    public static function publicationNecessaryForm(?EventRegistration $record = null): array
    {
        return $record->event->abstract_neccessary ?? False
            ? [Forms\Components\Wizard\Step::make('Publikációk')
                ->schema([
                    ...Event\PublicationForm::schema(),
                ])]
            : [];
    }


    public static function extraForm(?EventRegistration $record = null): array
    {
        $custom_form = $record->event->reg_form ?? False;

        return
            $custom_form
            ?
            [Forms\Components\Wizard\Step::make('További kérdések')
                ->schema([
                    ...Event\ExtraFormForm::schema(
                        customForm: $custom_form,
                        event_reg: $record,
                        attribute_name: 'reg_form_response'
                    ),
                ])]
            : [];
    }

    public static function extraFormView(EventRegistration $record): array
    {
        $custom_form = $record->event->reg_form ?? False;
        $custom_form_response = $record->reg_form_response;

        return
            $custom_form
            ?
            [Infolists\Components\Tabs\Tab::make('További kérdések')
                ->schema([
                    ...Event\ExtraFormView::schema(
                        customForm: $custom_form,
                        responses: $custom_form_response
                    ),
                ])]
            : [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventRegistrations::route('/'),
            'create' => Pages\CreateEventRegistration::route('/{eventslug}/create'),
            'view' => Pages\ViewEventRegistration::route('/{record}'),
            'edit' => Pages\EditEventRegistration::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('resource.title.event_registration');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.title.event_registration');
    }
}
