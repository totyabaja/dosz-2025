<?php

namespace App\Filament\Pages;

use App\Models\Scientific\DoctoralSchool;
use App\Models\Scientific\ScientificField;
use App\Models\Scientific\ScientificState;
use App\Models\Scientific\ScientificSubfield;
use App\Models\Scientific\University;
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
                    Forms\Components\Wizard\Step::make(mb_ucfirst(__('reg.menu.personal')))
                        ->schema(
                            $this->getPersonalFormContent(),
                        ),
                    Forms\Components\Wizard\Step::make(mb_ucfirst(__('reg.menu.contact')))
                        ->schema([
                            ...$this->getContactFormContent(),
                        ]),
                    Forms\Components\Wizard\Step::make(mb_ucfirst(__('reg.menu.scientific')))
                        ->schema(
                            $this->getGeneralFormContent(),
                        ),
                    Forms\Components\Wizard\Step::make(mb_ucfirst(__('reg.menu.extra')))
                        ->schema(
                            $this->getExtraFormContent(),
                        ),
                    Forms\Components\Wizard\Step::make(mb_ucfirst(__('reg.menu.finalize')))
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
            Forms\Components\Fieldset::make(mb_ucfirst(__('reg.fieldset.full_name')))->schema([
                Forms\Components\TextInput::make('lastname')
                    ->label(mb_ucfirst(__('resource.components.lastname')))
                    ->required()
                    ->minLength(2)
                    ->maxLength(255),
                Forms\Components\TextInput::make('firstname')
                    ->label(mb_ucfirst(__('resource.components.firstname')))
                    ->required()
                    ->minLength(2)
                    ->maxLength(255),
            ]),

            Forms\Components\Fieldset::make('logins')
                ->label(mb_ucfirst(__('reg.fieldset.login_values')))
                ->schema([
                    $this->getEmailFormComponent()
                        ->helperText(__('reg.message.don_not_email')),
                    $this->getPasswordFormComponent()
                        ->columnStart(1),
                    $this->getPasswordConfirmationFormComponent(),
                ]),

        ];
    }

    public static function getContactFormContent(): array
    {
        return [
            Forms\Components\Fieldset::make(mb_ucfirst(__('reg.fieldset.contacts')))
                ->schema([
                    Forms\Components\TextInput::make('email_intezmenyi')
                        ->label(mb_ucfirst(__('reg.input.email_intezmenyi')))
                        ->email()
                        ->nullable(),
                    Forms\Components\TextInput::make('mobil')
                        ->label(mb_ucfirst(__('reg.input.mobil')))
                        ->nullable()
                        ->tel()
                        ->telRegex('/^[+]*[0-9]{1,4}[-\s\.\/0-9]*$/')
                        ->hint('+36 20-310-7686')
                        ->maxLength(50),
                ]),

            Forms\Components\Fieldset::make('address')
                // ->relationship('address')
                ->label(mb_ucfirst(__('reg.fieldset.address')))
                ->schema([
                    Forms\Components\Grid::make()
                        ->columns(4)
                        ->schema([
                            Forms\Components\TextInput::make('postal_code')
                                ->label(mb_ucfirst(__('resource.components.zip')))
                                ->maxLength(7)
                                ->requiredWithAll(['country', 'city', 'street'])
                                ->columnSpan(1), // 1 résznyi helyet foglal
                            Forms\Components\TextInput::make('country')
                                ->label(mb_ucfirst(__('resource.components.country')))
                                ->autocomplete(false)
                                ->datalist([
                                    'Magyarország',
                                    'Románia',
                                    'Szlovákia',
                                    'Szerbia',
                                ])
                                ->hint(__('pl.: Magyarország'))
                                ->requiredWithAll(['postal_code', 'city', 'street'])
                                ->columnSpan(3), // 3 résznyi helyet foglal

                            Forms\Components\TextInput::make('city')
                                ->label(mb_ucfirst(__('resource.components.city')))
                                ->autocomplete(false)
                                ->requiredWithAll(['postal_code', 'country', 'street'])
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('street')
                                ->label(mb_ucfirst(__('resource.components.street')))
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
                    ->label(mb_ucfirst(__('resource.components.scientific_department')))
                    ->nullable()
                    ->options(fn() => ScientificState::pluck('name', 'id'))
                    ->preload(), // TODO: kell a kapcsolat
                Forms\Components\TextInput::make('fokozateve')
                    ->label(mb_ucfirst(__('resource.components.fokozat_eve')))
                    ->numeric()
                    ->nullable(),
            ]),

            Forms\Components\Select::make('universities')
                ->label(mb_ucfirst(__('resource.components.university')))
                ->options(
                    fn() =>
                    University::all()
                        ->sortBy('filament_full_name')
                        ->mapWithKeys(fn($item) => [$item->id => $item->filament_full_name])

                ) // Egyetemek listája
                ->live() // Figyelje a változást
                ->searchable()
                ->preload()
                ->afterStateUpdated(function (callable $set, $state) {
                    // Az egyetem kiválasztása után töröljük a doktori iskola mezőt
                    $set('doctoral_school_id', null);
                }),

            Forms\Components\Select::make('doctoral_school_id')
                ->label(mb_ucfirst(__('resource.components.doctoral_school')))
                ->options(function (callable $get) {
                    $universityId = $get('universities'); // Kiválasztott egyetem ID-je
                    if (! $universityId) {
                        return DoctoralSchool::all()
                            ->sortBy('filament_full_name')
                            ->mapWithKeys(fn($item) => [$item->id => $item->filament_full_name]); // Alapértelmezett lista, ha nincs szűrés
                    }

                    // Szűkített lista az adott egyetem alapján
                    return DoctoralSchool::where('university_id', $universityId)
                        ->get()
                        ->sortBy('filament_full_name')
                        ->mapWithKeys(fn($item) => [$item->id => $item->filament_full_name]);
                })
                ->live()
                ->searchable()
                ->preload()
                ->afterStateUpdated(function (callable $set, $state) {
                    // A doktori iskola kiválasztása után frissítjük az egyetemet
                    $universityId = DoctoralSchool::find($state)?->university_id;
                    $set('universities', $universityId);
                })
                ->afterStateHydrated(function ($state, $set, $get) {
                    $universityId = DoctoralSchool::find($state)?->university_id;
                    $set('universities', $universityId);
                }),

            Forms\Components\TextInput::make('disszertacio')
                ->label(mb_ucfirst(__('resource.components.disszertacio'))),
            Forms\Components\TextInput::make('kutatohely')
                ->label(mb_ucfirst(__('resource.components.kutatohely'))),
            Forms\Components\Grid::make()->columns(2)->schema([
                Forms\Components\Toggle::make('multi_tudomanyag')
                    ->label(mb_ucfirst(__('resource.components.multi_tudomanyag'))),
                Forms\Components\Toggle::make('tudfokozat')
                    ->label(mb_ucfirst(__('resource.components.tudfokozat'))),
            ]),
        ];
    }

    public static function getExtraFormContent(): array
    {
        // TODO
        return [
            Forms\Components\Repeater::make('scientific_fields_users')
                ->label(mb_ucfirst(__('resource.components.scientific_fields')))
                // ->relationship('scientific_fields_users')
                ->schema([
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\Select::make('scientific_fields')
                            ->label(mb_ucfirst(__('resource.components.scientific_field')))
                            ->options(fn() => ScientificField::all()->pluck('name', 'id'))
                            ->live() // Figyelje a változást
                            ->searchable()
                            ->preload()
                            ->afterStateUpdated(function (callable $set, $state) {
                                $set('scientific_subfield_id', null);
                            }),

                        Forms\Components\Select::make('scientific_subfield_id')
                            ->label(mb_ucfirst(__('resource.components.scientific_subfield')))
                            ->options(function (callable $get) {
                                $scientificFieldId = $get('scientific_fields'); // Kiválasztott egyetem ID-je
                                if (! $scientificFieldId) {
                                    return ScientificSubfield::pluck('name', 'id'); // Alapértelmezett lista, ha nincs szűrés
                                }

                                // Szűkített lista az adott egyetem alapján
                                return ScientificSubfield::where('scientific_field_id', $scientificFieldId)->pluck('name', 'id');
                            })
                            ->live()
                            ->searchable()
                            ->preload()
                            ->afterStateUpdated(function (callable $set, $state) {
                                // A doktori iskola kiválasztása után frissítjük az egyetemet
                                $scientificFieldId = ScientificSubfield::find($state)?->scientific_field_id;
                                $set('scientific_fields', $scientificFieldId);
                            })
                            ->afterStateHydrated(function ($state, $set) {
                                $scientificFieldId = ScientificSubfield::find($state)?->scientific_field_id;
                                $set('scientific_fields', $scientificFieldId);
                            }),
                    ]),

                    Forms\Components\TagsInput::make('keywords')
                        ->label(mb_ucfirst(__('resource.components.keywords')))
                        ->helperText(__('reg.message.tab_help'))
                        ->splitKeys(['Tab', ',']),
                ]),
        ];
    }

    protected function getFinalizeRegContent(): array
    {
        return [
            Forms\Components\Checkbox::make('accept')
                ->label(__('resource.messages.hozzajarulas'))
                ->accepted()
                ->required()
                ->afterStateUpdated(function (callable $set, $state) {
                    // Ha be van pipálva, állítsa be a jelenlegi dátumot
                    $set('adatvedelmit_elfogadta', $state ? now()->toDateString() : null);
                }),
        ];
    }
}
