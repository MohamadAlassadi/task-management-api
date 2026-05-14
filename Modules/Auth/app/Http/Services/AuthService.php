<?php
namespace Modules\Auth\App\Http\Services;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Modules\User\App\Models\User;
use Modules\User\Resources\UserResource;
use Modules\Auth\Enums\TokenAbility; 
use Carbon\Carbon;  
use Exception;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Validator;
use Modules\Auth\App\Http\Resources\AuthResource;
class AuthService
{
    public function register(array $data)
    {
         try{
            $user = User::create([
            'name'=>$data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
           ]);
           return [
            'success' => true,
            'message' => 'User created successfully',
            'user'    => new AuthResource($user)
            ];
        }catch (Exception $e) {
            Log::error('AUTH-007-400:Error Signing Up', ['error' => $e->getMessage()]);
             return [
            'success' => false,
            'message' => $e->getMessage(),];
        }
    }
    public function login(array $data)
    {  
        
        try {
            $email = $data['email'];
            $password = $data['password'];

           $user = User::where('email', $email)->first();

            if (!$user || !Hash::check($password, $user->password)) {
              return false;
            }
            $accessToken = $user->createToken('access-token');
            $refreshToken = $user->createToken('refresh-token');

                return [
                'message' => 'Login successful',
                'access_token' => $accessToken->plainTextToken,
                'refresh_token' => $refreshToken->plainTextToken,
                'user' => new AuthResource($user),
            ];
        } catch (Exception $e) {
            Log::error('AUTH-008-400: Error during user login', ['error' => $e->getMessage()]);
            return [
            'success' => false,
            'message' => $e->getMessage(),];;
        }
    }


    public function logout($user)
    {
        try 
        {
            $token = $user->currentAccessToken();
            if ($token) {
                $token->delete();
            }

            return [
                'success' => true,
                'message' => 'Logged out successfully'
            ];
        } catch (Exception $e) {
            Log::error('AUTH-009-400: Error during user logout', ['error' => $e->getMessage()]);
            return [
            'success' => false,
            'message' => $e->getMessage(),];;
        }
    }


    public function refreshToken(User $user)
    {
        try {
            $currentToken = $user->currentAccessToken();

            if (!$currentToken) {
                throw new Exception('Current token not found.');
            }

            $currentToken->delete();

            $newToken = $user->createToken('api-token')->plainTextToken;

            $refreshToken = $user->createToken('refresh-token')->plainTextToken;

            return [
                'user' => $user,
                'access_token' => $newToken,
                'refresh_token' => $refreshToken,
                'expires_in' => 60 * 60,
            ];
        } catch (Exception $e) {
            Log::error('Refresh Token Error', [
                'user_id' => $user->id ?? null,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

           return [
            'success' => false,
            'message' => $e->getMessage(),];;
        }
        }
    }
