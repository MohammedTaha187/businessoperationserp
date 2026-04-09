<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Services\Api\V1\AuthService;
use App\Traits\BaseApiResponse;
use Exception;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    use BaseApiResponse;

    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validate();
            $result = $this->authService->registerUser($data);

            return $this->successResponse([
                'token' => $result['token'],
                'user' => new UserResource($result['user']),
            ], __('lang.User registered successfully'), 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $result = $this->authService->loginUser($request->validated());

            return $this->successResponse([
                'token' => $result['token'],
                'user' => new UserResource($result['user']),
            ], __('lang.User logged in successfully'), 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function forgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        try {
            $this->authService->sendPasswordResetToken($request->email());

            return $this->successResponse([], __('lang.Password reset token sent successfully'), 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            $this->authService->resetPassword($request->all());

            return $this->successResponse(null, __('lang.Password reset successfully'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function me()
    {
        try {
            $user = $this->authService->getCurrentUser();

            return $this->successResponse(new UserResource($user), __('lang.User profile retrieved successfully'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 401);
        }
    }

    public function logout(Request $request)
    {
        $this->authService->logoutUser();

        return $this->successResponse(null, __('lang.User logged out successfully'));
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
        ]);

        try {
            $this->authService->verifyEmail($request->all());

            return $this->successResponse(null, __('lang.Email verified successfully'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 422);
        }
    }

    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            $this->authService->resendVerification($request->email);

            return $this->successResponse(null, __('lang.Verification link sent successfully'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    /**
     * Redirect the user to the provider authentication page.
     */
    public function redirectToProvider($provider)
    {
        $redirectUrl = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();

        return response()->json(['url' => $redirectUrl]);
    }

    /**
     * Obtain the user information from provider.
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();

            $result = $this->authService->socialLogin($provider, $socialUser);

            $token = $result['token'];
            $frontendUrl = env('FRONTEND_URL') ?: 'http://localhost:3003';

            return redirect($frontendUrl . '/auth/callback?token=' . $token);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }
}
