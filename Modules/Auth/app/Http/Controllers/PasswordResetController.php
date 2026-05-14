<?php

namespace Modules\Auth\App\Http\Controllers;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Auth\App\Http\Services\PasswordResetService;
use Modules\Auth\App\Http\Requests\ForgetPasswordRequest;
use Modules\Auth\App\Http\Requests\VerifyTokenRequest;
use Modules\Auth\App\Http\Requests\ResetPasswordRequest;
use Exception;

class PasswordResetController extends ApiController
{
    protected PasswordResetService $service;

    public function __construct(PasswordResetService $service)
    {
        $this->service = $service;
    }

     public function forgetPassword(ForgetPasswordRequest $request)
    {
        try {
            $success = $this->service->sendResetCode($request->email);

            if (!$success) {
                return $this->errorResponse('AUTH-010-404', 'Invalid email');
            }

            return $this->successResponse(null, 'A verification code has been sent to your email address.');
        } catch (Exception $e) {
            Log::error('AUTH-011-400: Forget Password Error', ['error' => $e->getMessage()]);
            return $this->errorResponse('AUTH-011-400', 'Failed to send verification code');
        }
    }

    public function verifyToken(VerifyTokenRequest $request)
    {
        try {
            $isValid = $this->service->verifyToken($request->email, $request->token);

            if (!$isValid) {
                return $this->errorResponse('AUTH-012-401', 'Invalid or expired verification code');
            }

            return $this->successResponse(null, 'Verification successful');
        } catch (Exception $e) {
            Log::error('AUTH-013-400: Verify Token Error', ['error' => $e->getMessage()]);
            return $this->errorResponse('AUTH-013-400', 'Failed to verify token');
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $done = $this->service->resetPassword($request->email, $request->password);

            if (!$done) {
                return $this->errorResponse('AUTH-014-404', 'User not found');
            }

            return $this->successResponse(null, 'Password changed successfully');
        } catch (Exception $e) {
            Log::error('AUTH-015-400: Reset Password Error', ['error' => $e->getMessage()]);
            return $this->errorResponse('AUTH-015-400', 'Failed to reset password');
        }
    }
}