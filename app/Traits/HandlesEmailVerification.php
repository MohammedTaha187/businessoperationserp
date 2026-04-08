<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Mail;

trait HandlesEmailVerification
{
    /**
     * Send verification OTP using a random 6-digit code.
     */
    protected function sendVerificationEmail(User $user): void
    {
        if ($user->email_verified_at) {
            return;
        }

        // Generate 6-digit numeric OTP
        $otp = (string) rand(100000, 999999);

        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(15);
        $user->save();

        Mail::send('emails.otp-verification', ['otp' => $otp, 'user' => $user], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject(__('lang.Email Verification Code'));
        });
    }
}
