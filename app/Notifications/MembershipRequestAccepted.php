<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MembershipRequestAccepted extends Notification
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
            ->subject('Tagsági kérelmed elfogadva')
            ->line("A(z) {$this->departmentName} osztályba benyújtott tagsági kérelmed elfogadásra került.")
            ->line('Kérlek, jelentkezz be a fiókodba a további részletekért.');
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
