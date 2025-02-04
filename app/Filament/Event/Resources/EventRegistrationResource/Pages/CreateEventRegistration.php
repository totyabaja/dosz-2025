<?php

namespace App\Filament\Event\Resources\EventRegistrationResource\Pages;

use App\Filament\Event\Resources\EventRegistrationResource;
use App\Models\Event\Event;
use App\Models\Event\EventRegistration;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class CreateEventRegistration extends CreateRecord
{
    protected static string $resource = EventRegistrationResource::class;

    protected ?Event $event = null;

    protected function beforeFill(): void
    {
        $slug = Request::route('eventslug'); // Slug kinyerése az útvonalból

        $this->event = Event::where('slug', $slug)->firstOrFail();

        $prev_reg = EventRegistration::query()
            ->where('event_id', $this->event->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($prev_reg) {
            redirect()->route('filament.event.resources.event-registrations.view', ['record' => $prev_reg->id]);
        }
    }

    protected function afterFill(): void
    {
        // Automatikusan kitölti az event_id mezőt a formban
        $this->form->fill([
            'event_id' => $this->event->id,
            'user_id' => Auth::id(),
        ]);

        session(['event_reg-abstract_neccessary' => $this->event->abstract_neccessary]);
        session(['event_reg-extra_form' => $this->event->reg_form->custom_form ?? null]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    protected function getFormActions(): array
    {
        return [
            //
        ];
    }
}
