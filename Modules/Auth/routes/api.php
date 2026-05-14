<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\App\Http\Controllers\AuthController;
use Modules\Auth\App\Http\Controllers\PasswordResetController;
Route::prefix('auth')->group(function () {
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout',[AuthController::class,'logout']);
    });

    Route::post('/token/refresh', [AuthController::class, 'refreshToken']);
    Route::post('/password/reset',[PasswordResetController::class,'resetPassword']);
    Route::post('/token/verify',[PasswordResetController::class,'verifyToken']);
    Route::post('/password/forget',[PasswordResetController::class,'forgetPassword']);
});

