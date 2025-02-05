<?php

namespace App\Filament\Event\Resources\EventRegistrationResource\Pages;

use App\Filament\Event\Resources\EventRegistrationResource;
use App\Models\Event\EventRegistrationStatus;
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
        $data['accepted_data_protection'] = $data['adatkezelesi'] ? now()->format('Y-m-d H:i:s') : null;
        $data['accepted_data_use'] = $data['hozzajarulas'] ?  now()->format('Y-m-d H:i:s') : null;

        return $data;
    }

    protected function afterSave(): void
    {
        EventRegistrationStatus::create([
            'event_registration_id' => $this->record->id,
            'event_status_id' => 0,
        ]);
    }
}
