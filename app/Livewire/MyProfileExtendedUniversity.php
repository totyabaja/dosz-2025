<?php

namespace App\Livewire;

use App\Filament\Components\ApprovedMembershipsComponent;
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
        $user = Auth::user();


        // 1. Folyamatban lévő kérelmek (még nem elfogadott tagságok)
        $pendingDepartments = $user->scientific_department_users()
            ->where('accepted', false)
            ->with('scientific_department')
            ->get();

        // 2. Meglévő tagságok (elfogadott tagságok)
        $approvedDepartments = $user->scientific_department_users()
            ->where('accepted', true)
            ->with('scientific_department')
            ->get()
            ->map(function ($item) {
                return [
                    "department_name" => $item->scientific_department->filament_name,
                    "accepted" => $item->accepted,
                ];
            })
            ->toArray();

        // 3. Azon osztályok lekérése, amelyekhez még nincs tagság
        $availableDepartments = \App\Models\Scientific\ScientificDepartment::query()
            ->whereNotIn('id', $user->scientific_department_users->pluck('scientific_department_id'))
            ->get()
            ->mapWithKeys(fn($item) => [$item->id => $item->filament_name]);


        return [
            // 1. Folyamatban lévő kérelmek megjelenítése
            Forms\Components\Section::make(mb_ucfirst(__('resource.tabs.pending_memberships')))
                ->schema(
                    $pendingDepartments->isEmpty()
                        ? [Forms\Components\Placeholder::make('no_pending')
                            ->hiddenLabel()
                            ->content('Nincs folyamatban lévő kérelmed.')]
                        : $pendingDepartments->map(
                            function ($membership) {
                                return Forms\Components\Grid::make(6)
                                    ->schema([
                                        // Kérelem megjelenítése
                                        Forms\Components\Placeholder::make('membership_name')
                                            ->label($membership->scientific_department->filament_name)
                                            ->content("Kérelem dátuma: " . $membership->request_datetime->format('Y-m-d'))
                                            ->columnSpan(4),

                                        // Visszavonás gomb hozzáadása
                                        Forms\Components\Actions::make([
                                            Action::make('cancel_request_' . $membership->id)  // Egyedi azonosító minden kéréshez
                                                ->label('Visszavonás')
                                                ->color('danger')
                                                ->icon('heroicon-o-trash')
                                                ->requiresConfirmation()  // Megerősítés kérés
                                                ->action(function () use ($membership) {
                                                    $membership->delete();

                                                    // Értesítés a felhasználónak
                                                    \Filament\Notifications\Notification::make()
                                                        ->title('Kérelem visszavonva')
                                                        ->body("A(z) {$membership->scientific_department->filament_name} tagsági kérelmed visszavonásra került.")
                                                        ->success()
                                                        ->send();

                                                    // Frissíti a formot, hogy eltűnjön a visszavont kérelem
                                                    $membership->refresh();
                                                }),
                                        ])->columnSpan(2)
                                    ]);
                            }
                        )->toArray()
                ),

            // 2. Új tagsági kérelem benyújtása
            Forms\Components\Section::make(mb_ucfirst(__('resource.tabs.new_membership_request')))
                ->schema([
                    Forms\Components\Grid::make(6)
                        ->schema([
                            Forms\Components\Select::make('scientific_department_id')
                                ->label('Válaszd ki a tudományos osztályt')
                                ->options($availableDepartments)
                                ->searchable()
                                ->preload()
                                ->live()
                                ->columnSpan(4)
                                ->suffixAction(
                                    Forms\Components\Actions\Action::make('request_membership')
                                        ->label('Tagság igénylése')
                                        ->color('primary')
                                        ->icon('heroicon-o-check')
                                        ->action(function ($get, $set, $record) use ($user) {
                                            $departmentId = $get('scientific_department_id');

                                            if ($departmentId) {
                                                $user->scientific_department_users()->create([
                                                    'scientific_department_id' => $departmentId,
                                                    'request_datetime' => now(),
                                                    'accepted' => false,
                                                ]);

                                                Notification::make()
                                                    ->title('Tagsági igény megküldve')
                                                    ->body('A(z) ' . \App\Models\Scientific\ScientificDepartment::find($departmentId)->filament_name . ' tagsági igényét sikeresen megküldte.')
                                                    ->success()
                                                    ->send();

                                                $record->refresh();
                                                $set('scientific_department_id', null);
                                            }
                                        })
                                        ->disabled(fn($get) => $get('scientific_department_id') === null),

                                ),
                        ])
                ]),

            // 3. Meglévő tagságok megjelenítése Repeater-rel
            Forms\Components\Section::make(mb_ucfirst(__('resource.tabs.memberships')))
                ->schema([
                    ApprovedMembershipsComponent::make('approved_memberships')
                        ->approvedDepartments($approvedDepartments),
                ])

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
