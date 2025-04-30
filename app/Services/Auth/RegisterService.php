<?php
namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    /**
     * Enregistre un nouvel utilisateur dans la base de données.
     *
     * @param  array  $data  Les données de l'utilisateur à enregistrer.
     * @return User  L'utilisateur enregistré.
     */
    public function register(array $data): User
    {
        $user = User::create([
            'prenom' => $data['prenom'],
            'initial_2_prenom' => getInitialPrenoms($data['prenom']),
            'nom_famille' => $data['nom_famille'],
            'nom_utilisateur' => $data['nom_utilisateur'],
            'email' => $data['email'],
            'mdp' => Hash::make($data['mdp']),
            'tel' => $data['tel'],
            'date_naissance' => $data['date_naissance'],
            'supprime' => false,
        ]);

        return $user;
    }
}