<?php

namespace App\Services\Api\V1;

use App\Models\User;
use App\Notifications\PasswordResetOtpNotification;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Notification;

class UserNotificationService
{
    public function sendWelcome(User $user): void
    {
        $user->notify(new WelcomeNotification(config('app.name')));
    }

    public function sendPasswordResetOtp(User $user, string $token): void
    {
        $user->notify(new PasswordResetOtpNotification($token));
    }

    protected function notifyActiveUsers(object $notification): void
    {
        $users = User::query()
            ->where('status', 'active')
            ->get();

        if ($users->isNotEmpty()) {
            Notification::send($users, $notification);
        }
    }
}
