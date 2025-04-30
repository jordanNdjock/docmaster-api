<?php

namespace App\Services\Auth;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class LogoutService
{
    /**
     * Déconnecte l'utilisateur donné en supprimant son token courant.
     *
     * @param  User|null  $user
     * @return void
     *
     * @throws AuthenticationException si l'utilisateur n'est pas authentifié
     * @throws \Throwable           pour toute autre erreur
     */
    public function logout(?User $user): void
    {
        if (! $user) {
            throw new AuthenticationException('Non authentifié.');
        }

        DB::transaction(function () use ($user) {
            $user->currentAccessToken()->delete();
        });
    }
}
