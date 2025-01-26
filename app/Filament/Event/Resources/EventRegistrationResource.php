<?php

namespace App\Filament\Event\Resources;

use App\Filament\Event\Resources\EventRegistrationResource\Pages;
use App\Filament\Event\Resources\EventRegistrationResource\RelationManagers;
use App\Models\Event\EventRegistration;
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
use Filament\Tables\Actions\Action;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema(fn($record) => [
                Forms\Components\Hidden::make('event_id')
                    ->required(),
                Forms\components\Hidden::make('user_id')
                    ->required(),

                Forms\Components\Wizard::make()
                    ->skippable()
                    ->skippable(fn($record) => $record !== null)
                    ->afterStateHydrated(fn($set) => static::userInfos($set))
                    ->persistStepInQueryString('reg-event')
                    ->schema(fn($get) => [
                        Forms\Components\Wizard\Step::make('presonl-infos')
                            ->label('Személyes adatok')
                            ->schema([
                                ...Event\UserForm::schema(),
                            ]),
                        Forms\Components\Wizard\Step::make('Számlázási adatok')
                            ->schema([
                                Forms\Components\Placeholder::make(__('Információ'))
                                    ->hiddenLabel(true)
                                    ->content(new HtmlString(
                                        'A számlázási adatok helyességéért te felelsz. <b>A helytelen adatok megadása miatti számlázási hibákért nem tudunk felelősséget vállalni. A jelentkezési határidő lezárultát követően nincs lehetőség a számlázási adatok módosítására.</b>'
                                    )),
                                ...Event\BillingForm::schema(),
                            ]),
                        ...static::publicationNecessary(),
                        ...static::extraForm(),
                        Forms\Components\Wizard\Step::make('Extra')
                            ->schema([
                                Forms\Components\Toggle::make('adatkezelesi')
                                    ->label('Megismertem az Adatkezelési Tájékoztatót és a Rendezvény Adatkezelési Tájékoztató Kivonatát.')
                                    ->onIcon('heroicon-m-document-check')
                                    ->offIcon('heroicon-m-exclamation-triangle')
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->accepted(),
                                Forms\Components\Toggle::make('hozzajarulas')
                                    ->label('Ezúton hozzájárulok, hogy a Doktoranduszok Országos Szövetsége, mint adatkezelő egyéni vagy nem tömeges (nem tömegről készült) fénykép vagy videófelvételeket készítsen a Rendezvény dokumentálása és jövőbeni népszerűsítése céljából. Hozzájárulás hiányában az Adatkezelő nem készíthet rólam egyéni vagy nem tömeges (tömegről készült) fénykép vagy videófelvételeket.')
                                    ->onIcon('heroicon-m-document-check')
                                    ->offIcon('heroicon-m-exclamation-triangle')
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->accepted(),
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
                    ->columnSpanFull(),
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
                Tables\actions\DeleteAction::make(),
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

    protected static function publicationNecessary(): array
    {
        return session()->get('abstract_neccessary', false)
            ? [Forms\Components\Wizard\Step::make('Publikációk')
                ->schema([
                    ...Event\PublicationForm::schema(),
                ])]
            : [];
    }

    protected static function extraForm(): array
    {
        // TODO: azt kell csekkolni, hogy van-e extra form
        $custom_form = \App\Models\Event\Event::where('slug', request()->eventslug)->first()?->reg_form->custom_form ?? null;

        //dd(request()->eventslug, \App\Models\Event\Event::where('slug', request()->eventslug)->get());

        return
            $custom_form
            ? [Forms\Components\Wizard\Step::make('További kérdések')
                ->schema([
                    ...Event\ExtraForm::schema($custom_form),
                ])]
            : [];;
    }

    protected static function userInfos($set)
    {
        $user = Auth::user();

        $set('regisztralo_name', $user->name);
        $set('notification_email', $user->email);
        $set('universities', $user->doctoral_school?->university_id ?? null);
        $set('doctoral_school_id', $user->doctoral_school?->id ?? null);

        if (! $user->address) {
            $set('event_invoice_address.zip', null);
            $set('event_invoice_address.country', 'Magyarország');
            $set('event_invoice_address.city', null);
            $set('event_invoice_address.address', null);
            return;
        }

        $set('event_invoice_address.zip', $user->address['postal_code']);
        $set('event_invoice_address.country', $user->address['country']);
        $set('event_invoice_address.city', $user->address['city']);
        $set('event_invoice_address.address', $user->address['street']);
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
}
