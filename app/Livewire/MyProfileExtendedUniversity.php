<?php

namespace App\Livewire;

use App\Filament\Pages\Registration;
use App\Models\User;
use App\Models\Scientific;
use Carbon\Carbon;
use Exception;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;

use function Filament\Support\is_app_url;

// TODO: a profilt itt kell kiegészíteni a többi személyes adattal

class MyProfileExtendedUniversity extends MyProfileComponent
{
    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public $user;

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $data = $this->getUser()->attributesToArray();

        $this->form->fill($data);
    }

    public function getUser(): Authenticatable&Model
    {
        $user = Filament::auth()->user();

        if (! $user instanceof Model) {
            throw new Exception('The authenticated user object must be an Eloquent model to allow the profile page to update it.');
        }

        return $user;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make()->schema([
                    Tabs\Tab::make(__('reg.menu.contact'))
                        ->schema([
                            ...Registration::getContactFormContent(),
                        ]),
                    Tabs\Tab::make(__('reg.menu.scientific'))
                        ->schema([
                            ...Registration::getGeneralFormContent(),
                        ]),
                    Tabs\Tab::make(mb_ucfirst(__('reg.menu.extra')))
                        ->schema([
                            ...Registration::getExtraFormContent(),
                        ]),
                    Tabs\Tab::make(mb_ucfirst(__('reg.menu.membership')))
                        ->schema([
                            ...static::getMembershipFormContent(),
                        ]),
                    Tabs\Tab::make(mb_ucfirst(__('reg.menu.gdpr')))
                        ->schema([
                            DateTimePicker::make('adatvedelmit_elfogadta')
                                ->readOnly()
                                ->suffixAction(
                                    fn($component) => Action::make('updateUser')
                                        ->label(fn($record): string => $record->editing_compiler ? 'INT' : 'AVV')
                                        ->form([])
                                        ->icon('heroicon-o-shield-check')
                                        ->tooltip('GDPR elolvasása és elfogadása')
                                        ->modalWidth('md')
                                        ->modalHeading('Adatvédelmi elfogadása')
                                        ->action(function ($component) {
                                            $record = Carbon::now();
                                            $user = User::find(Auth::user()->id);

                                            $user->adatvedelmit_elfogadta = $record;
                                            $user->save();

                                            $component->state($record->format('Y-m-d H:i:s'));
                                        })
                                        ->modalDescription('Are you sure you\'d like to delete this post? This cannot be undone.'),
                                ),
                            // $this->getGDPRContent(),
                        ]),
                ])->persistTabInQueryString('my-profile-custom-tabs'),
            ])
            ->operation('edit')
            ->model($this->getUser())
            ->statePath('data');
    }

    public function submit()
    {
        try {
            $data = $this->form->getState();

            $this->handleRecordUpdate($this->getUser(), $data);

            Notification::make()
                ->title('Profile updated')
                ->success()
                ->send();

            $this->redirect('my-profile', navigate: FilamentView::hasSpaMode() && is_app_url('my-profile'));
        } catch (\Throwable $th) {
            Notification::make()
                ->title('Failed to update.' . $th->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        return $record;
    }

    public function render(): View
    {
        return view('livewire.my-profile-extended-university');
    }

    protected static function getMembershipFormContent(): array
    {
        return [
            Forms\Components\Section::make('scientific_department_section')
                ->label('Tudományos osztály tagságok')
                ->hiddenLabel(false)
                ->schema([
                    Forms\Components\Repeater::make('scientific_department_users')
                        ->relationship('scientific_department_users')
                        ->hiddenLabel()
                        ->reorderable(false)
                        ->deletable(false)
                        ->schema([
                            Forms\Components\Grid::make([
                                'default' => 6,
                            ])
                                ->schema([
                                    // Scientific Department select
                                    Forms\Components\Select::make('scientific_department_id')
                                        ->relationship('scientific_department', 'id')
                                        ->options(
                                            fn() => Scientific\ScientificDepartment::all()
                                                ->sortBy('filament_name')
                                                ->pluck('name.' . session()->get('locale', 'hu'), 'id')
                                                ->toArray(),
                                        )
                                        ->searchable()
                                        ->preload()
                                        ->disabled(fn($get) => $get('id') != null) // Engedélyezett csak új elemnél
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                        ->columnSpan(2),

                                    // Checkbox - Accepted
                                    Forms\Components\Checkbox::make('pivot.accepted')
                                        ->label('Tag?')
                                        ->hidden(fn($get) => $get('id') == null) // Csak meglévő elemnél látható
                                        ->disabled(),

                                    // DateTime Pickers - Meglévő adatokhoz
                                    Forms\Components\DateTimePicker::make('pivot.request_datetime')
                                        ->label('Request_date')
                                        ->hidden(fn($get) => $get('id') == null)
                                        ->disabled(),

                                    Forms\Components\DateTimePicker::make('pivot.acceptance_datetime')
                                        ->label('Acceptance_date')
                                        ->hidden(fn($get) => $get('id') == null)
                                        ->disabled(),

                                    // Akciók
                                    Actions::make([
                                        // Visszavonás - Csak meglévő elemnél látható
                                        Actions\Action::make('visszavonas_scientific_department')
                                            ->label('Visszavonás')
                                            ->color('danger')
                                            ->icon('heroicon-o-trash')
                                            ->disabled(fn($get) => $get('id') == null)
                                            ->action(function ($record) {
                                                // Új kapcsolat létrehozása
                                                $record->delete();

                                                Notification::make()
                                                    ->title('Új tagsági igény visszavonása')
                                                    ->body('A tagsági igényét a(z) ' . ($record->scientific_department->name) . ' sikeresen megküldte.')
                                                    ->success()
                                                    ->send();
                                            }),
                                    ])->hidden(fn($get) => $get('id') == null),
                                    Actions::make([
                                        // Tagság igénylése - Csak új elemnél látható
                                        Actions\Action::make('save_scientific_department')
                                            ->label('Tagság igénylése')
                                            ->color('primary')
                                            ->icon('heroicon-o-check')
                                            // ->hidden(fn ($get) => $get('id') != null)
                                            ->action(function ($set, $get) {
                                                // Új kapcsolat létrehozása
                                                $user = \App\Models\User::findOrFail($get('../../id'));
                                                $user->scientific_department_users()->create([
                                                    'scientific_department_id' => $get('scientific_department_id'),
                                                    'request_datetime' => now(),
                                                    'accepted' => false, // Alapértelmezett érték
                                                ]);

                                                Notification::make()
                                                    ->title('Új tagsági igény megküldve')
                                                    ->body('A tagsági igényét a(z) ' . (Scientific\ScientificDepartment::findOrFail($get('scientific_department_id'))->filament_name) . ' sikeresen megküldte.')
                                                    ->success()
                                                    ->send();
                                            }),
                                    ])->hidden(fn($get) => $get('id') != null),
                                ]),
                        ])
                        ->columnSpanFull(),

                ]),
        ];
    }

    protected function getGDPRContent(): array
    {
        return [
            DateTimePicker::make('adatvedelmit_elfogadta')
                // ->readOnly()
                ->suffixAction(
                    Action::make('dsds')
                        ->icon('heroicon-m-x-mark')
                        ->modal(true)
                        ->form([
                            TextInput::make('dsdsAsd'),
                        ])
                ),
        ];
    }
}
