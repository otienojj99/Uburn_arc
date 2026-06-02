<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthServices
{
    /**
     * Attempt to log in the user.
     *
     * @throws ValidationException
     */


    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();
        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Your account is inactive. Please contact administrator.'],
            ]);
        }

        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * Log out the user (revoke current access token).
     */

    public function logout(User $user): void
    {
        /** @var \Laravel\Sanctum\PersonalAccessToken $token */
        $token = $user->currentAccessToken();

        if ($token) {
            $token->delete();
        }
    }

    /**
     * Get authenticated user.
     */
    public function getAuthenticatedUser(User $user): User
    {
        return $user;
    }
}
