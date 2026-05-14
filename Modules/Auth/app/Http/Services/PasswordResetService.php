<?php

namespace Modules\Auth\App\Http\Services;

use Modules\User\App\Models\User;
use Modules\Auth\App\Models\Password_reset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;
class PasswordResetService
{
    public function sendResetCode(string $email)
    {
        try {
            $user = User::where('email', $email)->first();
            if (!$user) {
                return false;
            }

            $token = Str::random(32);;

            Password_reset::where('email', $email)->delete();

            Password_reset::create([
                'email' => $email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]);

            Mail::raw(
                "Hi there, Your verification code is: $token \n\nPlease use this code to reset your password. If you did not request this, please ignore this email.\n\nBest regards,\nSupport Team",
                function ($message) use ($email) {
                    $message->to($email)->subject('Your OTP Verification Code');
                }
            );

            return true;
        } catch (Exception $e) {
            Log::error('AUTH-016-400: PasswordResetService sendResetCode Error', ['error' => $e->getMessage()]);
            return  $e->getMessage();
        }
    }

    public function verifyToken(string $email, string $tokenCode): bool
    {
        try {
            $token = Password_reset::where('email', $email)->first();

            if (!$token || !Hash::check($tokenCode, $token->token)) {
                return false;
            }

            $token->delete();

            return true;
        } catch (Exception $e) {
            Log::error('AUTH-017-400: PasswordResetService verifyToken Error', ['error' => $e->getMessage()]);
            return  $e->getMessage();
        }
    }

    public function resetPassword(string $email, string $newPassword): bool
    {
        try {
            $user = User::where('email', $email)->first();

            if (!$user) {
                return false;
            }

            $user->password = Hash::make($newPassword);
            $user->save();

            return true;
        } catch (Exception $e) {
            Log::error('AUTH-018-400: PasswordResetService resetPassword Error', ['error' => $e->getMessage()]);
            return  $e->getMessage();
        }
    }
}