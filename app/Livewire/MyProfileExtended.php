<?php

namespace App\Livewire;

use App\Filament\Admin\Resources\UserResource;
use App\Settings\MailSettings;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions\Action as ActionsAction;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

use function Filament\Support\is_app_url;

class MyProfileExtended extends MyProfileComponent
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

    public function getUser(): Authenticatable & Model
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
                SpatieMediaLibraryFileUpload::make('media')->label('Avatar')
                    ->collection('avatars')
                    ->avatar()
                    ->required(),
                Grid::make()->schema([
                    /*TextInput::make('username')
                        ->disabled()
                        ->required(),
                        */
                    TextInput::make('email')
                        ->disabled()
                        ->required()
                        ->suffixAction(
                            fn($record) => ActionsAction::make('editEmail')
                                ->label('Szerkesztés')
                                ->icon('heroicon-o-pencil')
                                ->modalHeading('E-mail cím szerkesztése')
                                ->modalSubheading('Az új e-mail cím mentése után újra validálni kell azt.')
                                ->modalButton('Mentés')
                                ->form([
                                    TextInput::make('email')
                                        ->label('Új e-mail cím')
                                        ->email()
                                        ->unique(table: 'users', column: 'email', ignoreRecord: true)
                                        ->required()
                                        ->unique('users', 'email')
                                        ->rules(['email', 'required']),
                                ])
                                ->action(fn($data, $record) => $record->update([
                                    'email' => $data['email'],
                                    'email_verified_at' => null, // Az e-mail újra validálásához
                                ]))
                                ->after(fn(MailSettings $settings, Model $record) => UserResource::doResendEmailVerification($settings, $record)) // E-mail validálás újraindítása
                        ),
                ]),
                Grid::make()->schema([
                    TextInput::make('firstname')
                        ->required(),
                    TextInput::make('lastname')
                        ->required()
                ]),
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
                ->title('Failed to update.')
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
        return view("livewire.my-profile-extended");
    }
}
