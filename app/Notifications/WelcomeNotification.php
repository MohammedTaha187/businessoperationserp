<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;

    public function __construct(protected string $appName) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Welcome to {$this->appName}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Welcome to {$this->appName}. Your account has been created successfully.")
            ->line('You can now sign in and start using the platform.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'welcome',
            'title' => 'Welcome to the platform',
            'message' => "Your {$this->appName} account is ready.",
        ];
    }
}
