<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    /**
     * Authentifie un utilisateur et retourne le token.
     *
     * @param  string  $email
     * @param  string  $password
     * @return array{user: User, token: string}
     *
     * @throws AuthenticationException
     */
    public function authenticate(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->mdp)) {
            throw new AuthenticationException('Identifiants invalides.');
        }

        $token = $user
            ->createToken("access_token_of_{$user->email}")
            ->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }
}
