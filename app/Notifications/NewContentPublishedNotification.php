<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContentPublishedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected string $contentType,
        protected string $title,
        protected ?string $url = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject("New {$this->contentType} available")
            ->greeting("Hello {$notifiable->name},")
            ->line("A new {$this->contentType} has been published: {$this->title}.");

        if ($this->url) {
            $mail->action('Open', $this->url);
        }

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_content',
            'content_type' => $this->contentType,
            'title' => $this->title,
            'url' => $this->url,
            'message' => "A new {$this->contentType} has been published: {$this->title}.",
        ];
    }
}
