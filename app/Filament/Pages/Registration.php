<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class Registration extends Register
{
    protected ?string $maxWidth = '4xl';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make(__('reg.menu.personal'))
                        ->schema(
                            $this->getPersonalFormContent(),
                        ),
                    Forms\Components\Wizard\Step::make(__('reg.menu.contact'))
                        ->schema([
                            $this->getEmailFormComponent(),
                            ...$this->getContactFormContent(),
                        ]),
                    Forms\Components\Wizard\Step::make(__('reg.menu.scientific'))
                        ->schema(
                            $this->getGeneralFormContent(),
                        ),
                    Forms\Components\Wizard\Step::make('Extra')
                        ->schema(
                            $this->getExtraFormContent(),
                        ),
                    Forms\Components\Wizard\Step::make('Finalize')
                        ->schema(
                            $this->getFinalizeRegContent(),
                        ),
                ])->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                    <x-filament::button
                        type="submit"
                        size="sm"
                        wire:submit="register"
                    >
                        Register
                    </x-filament::button>
                    BLADE)))
                    ->persistStepInQueryString(),
            ]);
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getPersonalFormContent(): array
    {
        return [
            Forms\Components\Fieldset::make(__('reg.fieldset.full_name'))->schema([
                Forms\Components\TextInput::make('firstname')
                    ->required()
                    ->minLength(2)
                    ->maxLength(255)
                    ->hint('Keresztnév'),
                Forms\Components\TextInput::make('lastname')
                    ->required()
                    ->minLength(2)
                    ->maxLength(255)
                    ->hint('Vezetéknév'),
            ]),

            Forms\Components\Fieldset::make(__('reg.fieldset.passwords'))->schema([
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]),

        ];
    }

    public static function getContactFormContent(): array
    {
        return [
            Forms\Components\Fieldset::make(__('reg.fieldset.contacts'))
                ->schema([
                    Forms\Components\TextInput::make('email_intezmenyi')
                        ->label(__('reg.input.email_intezmenyi'))
                        ->email()
                        ->nullable(),
                    Forms\Components\TextInput::make('mobil')
                        ->label(__('reg.input.mobil'))
                        ->nullable()
                        ->tel()
                        ->telRegex('/^[+]*[0-9]{1,4}[-\s\.\/0-9]*$/')
                        ->hint('+36 20-310-7686')
                        ->maxLength(50),
                ]),

            Forms\Components\Fieldset::make(__('reg.fieldset.address'))
                // ->relationship('address')
                ->schema([
                    Forms\Components\Grid::make()
                        ->columns(4)
                        ->schema([
                            Forms\Components\TextInput::make('postal_code')
                                ->label(__('Postal Code'))
                                ->maxLength(7)
                                ->requiredWithAll(['country', 'city', 'street'])
                                ->columnSpan(1), // 1 résznyi helyet foglal
                            Forms\Components\TextInput::make('country')
                                ->label(__('Country'))
                                ->autocomplete(false)
                                ->datalist([
                                    'Magyarország',
                                    'Románia',
                                    'Szlovákia',
                                    'Szerbia',
                                ])
                                ->hint(__('Enter the country name'))
                                ->requiredWithAll(['postal_code', 'city', 'street'])
                                ->columnSpan(3), // 3 résznyi helyet foglal

                            Forms\Components\TextInput::make('city')
                                ->label(__('City'))
                                ->autocomplete(false)
                                ->requiredWithAll(['postal_code', 'country', 'street'])
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('street')
                                ->label(__('Street'))
                                ->autocomplete(false)
                                ->hint(__('Nagy Lajos utca 23. 2/46'))
                                ->requiredWithAll(['postal_code', 'city', 'country'])
                                ->columnSpan(3),
                        ]),
                ]),

        ];
    }

    public static function getGeneralFormContent(): array
    {
        return [
            // + státusz

            Forms\Components\Grid::make()->columns(2)->schema([

                Forms\Components\Select::make('scientific_state_id')
                    ->nullable()
                    ->options(fn () => \App\Models\ScientificState::pluck('name', 'id'))
                    ->preload(), // TODO: kell a kapcsolat
                Forms\Components\TextInput::make('fokozateve')
                    ->numeric()
                    ->nullable(),
            ]),

            Forms\Components\Select::make('universities')
                ->label(__('University'))
                ->options(fn () => \App\Models\University::pluck('full_name', 'id')) // Egyetemek listája
                ->live() // Figyelje a változást
                ->searchable()
                ->preload()
                ->afterStateUpdated(function (callable $set, $state) {
                    // Az egyetem kiválasztása után töröljük a doktori iskola mezőt
                    $set('doctoral_school_id', null);
                }),

            Forms\Components\Select::make('doctoral_school_id')
                ->label(__('Doctoral School'))
                ->options(function (callable $get) {
                    $universityId = $get('universities'); // Kiválasztott egyetem ID-je
                    if (! $universityId) {
                        return \App\Models\DoctoralSchool::pluck('full_name', 'id'); // Alapértelmezett lista, ha nincs szűrés
                    }

                    // Szűkített lista az adott egyetem alapján
                    return \App\Models\DoctoralSchool::where('university_id', $universityId)->pluck('full_name', 'id');
                })
                ->live()
                ->searchable()
                ->preload()
                ->afterStateUpdated(function (callable $set, $state) {
                    // A doktori iskola kiválasztása után frissítjük az egyetemet
                    $universityId = \App\Models\DoctoralSchool::find($state)?->university_id;
                    $set('universities', $universityId);
                })
                ->afterStateHydrated(function ($state, $set, $get) {
                    $universityId = \App\Models\DoctoralSchool::find($state)?->university_id;
                    $set('universities', $universityId);
                }),

            Forms\Components\TextInput::make('disszertacio'),
            Forms\Components\TextInput::make('kutatohely'),
            Forms\Components\Grid::make()->columns(2)->schema([
                Forms\Components\Toggle::make('multi_tudomanyag'),
                Forms\Components\Toggle::make('tudfokozat'),
            ]),
        ];
    }

    public static function getExtraFormContent(): array
    {
        // TODO
        return [
            Forms\Components\Repeater::make('scientific_fields_users')
                // ->relationship('scientific_fields_users')
                ->schema([
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\Select::make('scientific_fields')
                            ->label('Scientific Field')
                            ->options(fn () => \App\Models\ScientificField::all()->pluck('name', 'id'))
                            ->live() // Figyelje a változást
                            ->searchable()
                            ->preload()
                            ->afterStateUpdated(function (callable $set, $state) {
                                $set('scientific_subfield_id', null);
                            }),

                        Forms\Components\Select::make('scientific_subfield_id')
                            ->label('Scientific Subfield')
                            ->options(function (callable $get) {
                                $scientificFieldId = $get('scientific_fields'); // Kiválasztott egyetem ID-je
                                if (! $scientificFieldId) {
                                    return \App\Models\ScientificSubfield::pluck('name', 'id'); // Alapértelmezett lista, ha nincs szűrés
                                }

                                // Szűkített lista az adott egyetem alapján
                                return \App\Models\ScientificSubfield::where('scientific_field_id', $scientificFieldId)->pluck('name', 'id');
                            })
                            ->live()
                            ->searchable()
                            ->preload()
                            ->afterStateUpdated(function (callable $set, $state) {
                                // A doktori iskola kiválasztása után frissítjük az egyetemet
                                $scientificFieldId = \App\Models\ScientificSubfield::find($state)?->scientific_field_id;
                                $set('scientific_fields', $scientificFieldId);
                            })
                            ->afterStateHydrated(function ($state, $set) {
                                $scientificFieldId = \App\Models\ScientificSubfield::find($state)?->scientific_field_id;
                                $set('scientific_fields', $scientificFieldId);
                            }),
                    ]),

                    Forms\Components\TagsInput::make('keywords')
                        ->splitKeys(['Tab', ',']),
                ]),
        ];
    }

    protected function getFinalizeRegContent(): array
    {
        return [
            Forms\Components\Checkbox::make('accept')
                ->label('Kifejezetten hozzájárulok, hogy a Doktoranduszok Országos Szövetsége, mint adatkezelő a fentiekben megadott személyes adataimat, beleértve az általam esetlegesen megadott különleges adataimat a szerződés teljesítéséhez kapcsolódó kérdések rendezése céljából kezelje. A hozzájárulás megadásának hiányában nem áll módunkban válaszolni a megkeresésre vagy teljesíteni a megfogalmazott kérést.')
                ->accepted()
                ->required()
                ->afterStateUpdated(function (callable $set, $state) {
                    // Ha be van pipálva, állítsa be a jelenlegi dátumot
                    $set('adatvedelmit_elfogadta', $state ? now()->toDateString() : null);
                }),
        ];
    }
}
