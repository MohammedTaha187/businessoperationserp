<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetOtpNotification extends Notification
{
    use Queueable;

    public function __construct(protected string $token) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Password Reset Code')
            ->greeting("Hello {$notifiable->name},")
            ->line('Use the following OTP / reset code to complete your password reset request:')
            ->line($this->token)
            ->line('This code will expire in 60 minutes.')
            ->line('If you did not request this, you can safely ignore this email.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'password_reset_otp',
            'title' => 'Password reset requested',
            'message' => 'A password reset code was issued for your account.',
        ];
    }
}
