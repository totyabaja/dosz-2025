<?php

namespace App\Filament\Event\Resources\EventRegistrationResource\Pages;

use App\Filament\Event\Resources\EventRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventRegistration extends EditRecord
{
    protected static string $resource = EventRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            //
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        //dd($data);
        return $data;
    }

    protected function beforeFill(): void
    {
        $event = $this->record->event; // Az esemény lekérése

        session(['event_reg-abstract_neccessary' => $event->abstract_neccessary]);
        session(['event_reg-extra_form' => $event->reg_form->custom_form ?? null]);
    }
}
