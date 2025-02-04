<?php

namespace App\Filament\Event\Resources\EventRegistrationResource\Pages;

use App\Filament\Admin\Resources\PageResource\Pages\EditPage;
use App\Filament\Event\Resources\EventRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEventRegistration extends ViewRecord
{
    protected static string $resource = EventRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn($record): bool => $record->event->event_registration_editable),
            Actions\DeleteAction::make()
                ->visible(fn($record): bool => $record->event->event_registration_editable),
        ];
    }

    protected function beforeFill(): void
    {
        $event = $this->record->event; // Az esemény lekérése

        session(['event_reg-abstract_neccessary' => $event->abstract_neccessary]);
        session(['event_reg-extra_form' => $event->reg_form->custom_form ?? null]);
    }

    /*protected function afterFill(): void
    {
        dd($this->form, $this->data);
    }*/
}
