<?php

namespace App\Observers;

use App\Models\Event\EventRegistration;
use App\Models\Event\EventRegistrationStatus;

class EventRegistrationObserver
{
    /**
     * Handle the EventRegistration "created" event.
     */
    public function created(EventRegistration $eventRegistration): void
    {
        EventRegistrationStatus::create([
            'event_registration_id' => $eventRegistration->id,
            'event_status_id' => 0,
        ]);
    }

    /**
     * Handle the EventRegistration "updated" event.
     */
    public function updated(EventRegistration $eventRegistration): void
    {
        //
    }

    /**
     * Handle the EventRegistration "deleted" event.
     */
    public function deleted(EventRegistration $eventRegistration): void
    {
        //
    }

    /**
     * Handle the EventRegistration "restored" event.
     */
    public function restored(EventRegistration $eventRegistration): void
    {
        //
    }

    /**
     * Handle the EventRegistration "force deleted" event.
     */
    public function forceDeleted(EventRegistration $eventRegistration): void
    {
        //
    }
}
