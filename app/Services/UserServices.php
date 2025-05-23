<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserServices
{
    /**
     * Return All the Users
     * @param int $per_page
     * @param mixed $page
     * 
     */
    public function getAllUsers(int $per_page = 10, ?int $page)
    {
        $page = $page ?: Paginator::resolveCurrentPage();

        $paginator = User::
            paginate(
                $per_page,
                ['*'],
                'page',
                $page
            );

        return [
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }

    /**
     * Summary of getUserById
     * @param string $id
     * @return User
     */
    public function getUserById(string $id)
    {
        return User::active()->findOrFail($id);
    }

    /**
     * Summary of updateUser
     * @param string $id
     * @param array $data
     * @return User
     */
    public function updateUser(string $id, array $data, ?string $path): User
    {
        $user = User::active()->findOrFail($id);
        $user->update([
            'prenom' => $data['prenom'],
            'initial_2_prenom' => getInitialPrenoms($data['prenom']),
            'nom_famille' => $data['nom_famille'],
            'nom_utilisateur' => $data['nom_utilisateur'],
            'email' => $data['email'],
            'tel' => $data['tel'],
            'date_naissance' => $data['date_naissance'],
            'localisation' => $data['localisation'] ?? null,
            'solde' => $data['solde'],
            'infos_paiement' => $data['infos_paiement'] ?? null,
            'photo_url' => $path ?? null,
        ]);
        Log::channel('admin_actions')->info('Utilisateur mis à jour ', [
            'id'           => $user->id,
            'email'        => $user->email,
            'nom_utilisateur' => $user->nom_utilisateur,
            'updated_by'   => auth('admin')->user() ? auth('admin')->user()->email : 'unknown',
        ]);        
        return $user;
    }

    /**
     * Summary of deleteUser
     * @param string $id
     * @return void
     */
    public function deleteUser(string $id): void
    {
        DB::transaction(function () use ($id){
            $user = User::findOrFail($id);
            $user->forceDelete();

            Log::channel('admin_actions')->info('Utilisateur supprimé ', [
                'id'           => $user->id,
                'email'        => $user->email,
                'nom_utilisateur' => $user->nom_utilisateur,
                'deleted_by'   => auth('admin')->user() ? auth('admin')->user()->email : 'unknown',
            ]);          
        });
    }
    
    public function blockUser(string $id){
        DB::transaction(function () use ($id){
            $user = User::active()->findOrFail($id);
            $user->update([ 'supprime' => true ]);

            Log::channel('admin_actions')->info('Utilisateur blocké ', [
                'id'           => $user->id,
                'email'        => $user->email,
                'nom_utilisateur' => $user->nom_utilisateur,
                'blocked_by'   => auth('admin')->user() ? auth('admin')->user()->email : 'unknown',
            ]); 
        });
    }

    public function restoreUser(string $id): void
    {
        DB::transaction(function () use ($id){
            $user = User::bloqued()->findOrFail($id);
            $user->update(['supprime' => false]);
            Log::channel('admin_actions')->info('Utilisateur restauré ', [
                'id'           => $user->id,
                'email'        => $user->email,
                'nom_utilisateur' => $user->nom_utilisateur,
                'restored_by'   => auth('admin')->user() ? auth('admin')->user()->email : 'unknown',
            ]);          
        });
    }
}