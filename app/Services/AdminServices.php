<?php

namespace App\Services;

use App\Models\Admin;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminServices
{
    /**
     * Authentifie un admin et renvoit un token
     */
    public function authenticate(string $email, string $mdp): array
    {
        $admin = Admin::
        where('email', $email)
        ->where('supprime', false)
        ->first();

        if (! $admin || !Hash::check($mdp, $admin->mdp)) {
            throw new AuthenticationException('Identifiants invalides ou administrateur supprimé.');
        }
        Log::channel('admin_actions')->info("Admin connecté avec succès : ".$admin->email);
        $token = $admin->createToken("access_token_of_{$admin->email}")->plainTextToken;
        return ['admin' => $admin, 'token' => $token];
    }

    /**
     * Deconnecte un admin et supprime son token
     */
    public function logout(?Admin $admin): void
    {
        if (! $admin) {
            throw new AuthenticationException('Non authentifié.');
        }

        DB::transaction(function () use ($admin) {
            $admin->currentAccessToken()->delete();
        });
        Log::channel('admin_actions')->info("Admin déconnecté avec succès : ".$admin->email);
    }
}