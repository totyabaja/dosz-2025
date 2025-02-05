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

    public function beforeFill()
    {
        $slug = Request::route('eventslug'); // Slug kinyerése az útvonalból
        $event = Event::where('slug', $slug)->firstOrFail();
        $user = Auth::user();

        $prev_reg = EventRegistration::query()
            ->where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if ($prev_reg) {
            return redirect()->route('filament.event.resources.event-registrations.view', ['record' => $prev_reg->id]);
        } else {
            $new_reg = EventRegistration::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'notification_email' => $user->email,
                'doctoral_school_id' => $user->doctoral_school_id,
                'scientific_department_id' => null,
                'event_invoice_address' => [],
                'reg_form_response' => [],
                'feedback_form_response' => [],
            ]);

            return redirect()->route('filament.event.resources.event-registrations.edit', ['record' => $new_reg->id]);
        }
    }

    protected function getFormActions(): array
    {
        return [
            //
        ];
    }
}
