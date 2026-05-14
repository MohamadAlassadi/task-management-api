<?php

namespace Modules\Auth\App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Support\Api\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Auth\App\Http\Requests\LoginRequest;
use Modules\Auth\App\Http\Requests\RegisterRequest;
use Modules\Auth\App\Http\Resources\AuthResource;
use Modules\Auth\App\Http\Services\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use Modules\User\App\Models\User;


class AuthController extends ApiController
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

     public function register(RegisterRequest $request)      
    {
        try {
            $user = $this->authService->register($request->validated());

            if (!$user['success']) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
            return $this->successResponse($user['user'], $user['message'], 200);
        } catch (Exception $e) {
            Log::error('AUTH-001-400: Error Signing Up', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to sign up'], 400);
        }
    }
    public function login(LoginRequest $request)
    {
        try {
            $data = $this->authService->login($request->validated());

            if (!$data) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            return response()->json($data, 200);
        } catch (Exception $e) {
            Log::error('AUTH-002-400: User Login Error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to login'], 400);
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->authService->logout($request->user());
            return response()->json(['message' => 'Logout successful'], 200);
        } catch (Exception $e) {
            Log::error('AUTH-003-400: User Logout Error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to logout'], 400);
        }
    }
public function refreshToken(Request $request)
{
    try {
        $user = $request->user();
        if (!$user) {
            return $this->errorResponse('AUTH-004-401', 'Unauthorized: Token missing or invalid');
        }

        $data = $this->authService->refreshToken($user);

        if (!$data) {
            return $this->errorResponse('AUTH-005-404', 'Token refresh failed');
        }

        return $this->successResponse($data, 'Token refreshed successfully');
    } catch (Exception $e) {
        Log::error('AUTH-006-400: Admin Refresh Token Error', ['error' => $e->getMessage()]);
        return $this->errorResponse('AUTH-006-400', 'Failed to refresh token');
    }
}
}
