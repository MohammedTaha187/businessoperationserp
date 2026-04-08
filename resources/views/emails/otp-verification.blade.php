<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; color: #2563eb; }
        .otp-code { font-size: 32px; font-weight: bold; text-align: center; letter-spacing: 5px; color: #1e40af; background: #f3f4f6; padding: 10px; border-radius: 5px; margin: 20px 0; }
        .footer { font-size: 12px; color: #666; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="header">{{ __('lang.Welcome') }}, {{ $user->name }}</h2>
        <p>{{ __('lang.Use the following code to verify your email address:') }}</p>
        <div class="otp-code">{{ $otp }}</div>
        <p>{{ __('lang.This code will expire in 15 minutes.') }}</p>
        <p>{{ __('lang.If you did not create an account, no further action is required.') }}</p>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('lang.All rights reserved.') }}
        </div>
    </div>
</body>
</html>
