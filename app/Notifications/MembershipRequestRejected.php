<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MembershipRequestRejected extends Notification
{
    use Queueable;

    public $departmentName;

    /**
     * Create a new notification instance.
     */
    public function __construct($departmentName)
    {
        $this->departmentName = $departmentName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Tagsági kérelmed elutasítva')
            ->line("A(z) {$this->departmentName} osztályba benyújtott tagsági kérelmed elutasításra került.")
            ->line('Ha kérdésed van, kérlek vedd fel a kapcsolatot az adminisztrátorral.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
