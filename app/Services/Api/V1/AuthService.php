<?php

namespace App\Services\Api\V1;

use App\Models\User;
use App\Traits\HandlesEmailVerification;
use App\Traits\InteractsWithApiCache;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    use HandlesEmailVerification, InteractsWithApiCache;

    public function __construct(protected UserNotificationService $userNotificationService) {}

    public function registerUser(array $data): array
    {
        // التحقق من الحظر قبل التسجيل
        $existingUser = User::where('email', $data['email'])->first();
        if ($existingUser && $existingUser->status === 'suspended') {
            throw new Exception(__('lang.Email blocked'), 403);
        }

        // إنشاء المستخدم
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'company_id' => $data['company_id'] ?? 1,
            'branch_id' => $data['branch_id'] ?? 1,
        ]);

        if (isset($data['image'])) {
            $user->update([
                'image' => $data['image']->store('users', 'public'),
            ]);
        }

        // تعيين الدور الافتراضي (طالب)
        $user->assignRole('student');
        $this->forgetPublicContentCache();
        $this->userNotificationService->sendWelcome($user);

        // إطلاق عملية التحقق من البريد
        $this->sendVerificationEmail($user);

        // إصدار توكن دخول باستخدام Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function loginUser(array $credentials): array
    {
        // محاولة جلب المستخدم بالبريد
        $user = User::where('email', $credentials['email'])->first();

        // التحقق من صحة كلمة المرور
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw new Exception(__('lang.Invalid credentials'), 401);
        }

        // التحقق من الحظر اليدوي
        if ($user->status === 'suspended') {
            throw new Exception(__('lang.Account blocked'), 403);
        }

        // إصدار توكن جديد باستخدام Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * تحديث بيانات الملف الشخصي
     */
    public function getCurrentUser(): User
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user) {
            throw new Exception(__('lang.Unauthorized'), 401);
        }

        return $user;
    }

    /**
     * تحديث بيانات الملف الشخصي
     */
    public function updateUser(User $user, array $data): User
    {
        if (isset($data['password'])) {
            // التحقق من كلمة المرور القديمة قبل السماح بالتغيير
            if (! isset($data['old_password']) || ! Hash::check($data['old_password'], $user->password)) {
                throw new Exception(__('lang.Current password is incorrect'), 422);
            }
            $user->password = Hash::make($data['password']);
        }

        $user->name = $data['name'] ?? $user->name;
        $user->email = $data['email'] ?? $user->email;
        $user->phone = $data['phone'] ?? $user->phone;

        if (isset($data['image'])) {
            $user->image = $data['image']->store('users', 'public');
        }

        $user->save();
        $this->forgetPublicContentCache();

        return $user;
    }

    /**
     * تسجيل الخروج وإبطال التوكنات
     */
    public function logoutUser(): void
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user) {
            $token = $user->currentAccessToken();
            if ($token instanceof \Laravel\Sanctum\PersonalAccessToken) {
                $token->delete();
            } else {
                $user->tokens()->delete();
            }
        }
    }

    /**
     * منطق استعادة كلمة المرور (إرسال الرمز)
     */
    public function sendPasswordResetToken(string $email): string
    {
        $user = User::where('email', $email)->first();
        if (! $user) {
            throw new Exception(__('lang.User not found'), 404);
        }

        // في نسخة الـ API الحالية سنقوم بتوليد كود بسيط أو توكن يدوي
        // لارافيل يوفر Password Broker ولكن للتبسيط في الـ API سنستخدم Token يدوي
        $token = bin2hex(random_bytes(20));

        // تخزين التوكن في جدول password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        $this->userNotificationService->sendPasswordResetOtp($user, $token);

        return $token;
    }

    /**
     * إعادة تعيين كلمة المرور باستخدام التوكن
     */
    public function resetPassword(array $data): void
    {
        $record = DB::table('password_reset_tokens')
            ->where('email', $data['email'])
            ->first();

        if (! $record || ! Hash::check($data['token'], $record->token)) {
            throw new Exception(__('lang.Invalid token'), 422);
        }

        // التحقق من صلاحية الوقت (مثلاً ساعة واحدة)
        if (Carbon::parse($record->created_at)->addHour()->isPast()) {
            throw new Exception(__('lang.Token expired'), 422);
        }

        // تحديث كلمة المرور
        $user = User::where('email', $data['email'])->first();
        $user->password = Hash::make($data['password']);
        $user->save();

        // حذف التوكن بعد الاستخدام
        DB::table('password_reset_tokens')->where('email', $data['email'])->delete();
    }

    /**
     * التحقق من البريد الإلكتروني باستخدام كود الـ OTP
     */
    public function verifyEmail(array $data): void
    {
        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            throw new Exception(__('lang.User not found'), 404);
        }

        if ($user->email_verified_at) {
            throw new Exception(__('lang.Email already verified'), 400);
        }

        if (! $user->otp_code || $user->otp_code !== $data['token']) {
            throw new Exception(__('lang.Invalid verification code'), 422);
        }

        if ($user->otp_expires_at && Carbon::parse($user->otp_expires_at)->isPast()) {
            throw new Exception(__('lang.Verification code expired'), 422);
        }

        $user->email_verified_at = now();
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();
        $this->forgetPublicContentCache();
    }

    /**
     * إعادة إرسال رابط التحقق
     */
    public function resendVerification(string $email): void
    {
        $user = User::where('email', $email)->first();

        if (! $user) {
            throw new Exception(__('lang.User not found'), 404);
        }

        if ($user->email_verified_at) {
            throw new Exception(__('lang.Email already verified'), 400);
        }

        $this->sendVerificationEmail($user);
    }

    /**
     * الدخول عبر مزودي الخدمة الاجتماعي (Google, Facebook)
     */
    public function socialLogin(string $provider, $socialUser): array
    {
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // تحديث بيانات الـ Provider إذا وجد المستخدم مسبقاً بنفس الإيميل
            $user->update([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ]);
        } else {
            // إنشاء مستخدم جديد إذا لم يكن موجوداً
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(bin2hex(random_bytes(10))), // كلمة مرور عشوائية
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'company_id' => 1, // قيم افتراضية للتجربة
                'branch_id' => 1,
            ]);

            $user->assignRole('employee');
        }

        // إرسال كود التحقق إذا لم يكن الحساب موثقاً
        if (!$user->email_verified_at) {
            $this->sendVerificationEmail($user);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
