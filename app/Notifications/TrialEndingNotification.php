<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TrialEndingNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $endDateFormatted;

    public function __construct($user, $endDateFormatted)
    {
        $this->user = $user;
        $this->endDateFormatted = $endDateFormatted;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Trial is Ending Soon')
            ->greeting('Hello ' . $this->user->name . ',')
            ->line('Your trial is ending on ' . $this->endDateFormatted . '.')
            ->action('Upgrade Now', url('/pricing'))
            ->line('Thank you for using our application!');
    }
}
