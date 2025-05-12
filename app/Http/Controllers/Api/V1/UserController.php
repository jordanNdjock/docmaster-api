<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\UserServices;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserController
{
    use ApiResponse;

    public function __construct(
        protected UserServices $userServices,
    ){}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $documents = $this->userServices->getAllUsers($per_page, $page);
        return $this->sendResponse(
            [
            'users' => $documents['data'],
            'meta' => $documents['meta']
            ],
            'Liste des utilisateurs récupérée avec succès.'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = $this->userServices->getUserById($id);
            return $this->sendResponse(
                $user,
                'Utilisateur recupéré avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Utilisateur non trouvé !', ['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $this->userServices->deleteUser($id);
            return $this->sendResponse(
                [],
                'Utilisateur supprimé avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Utilisateur non trouvé !', ['error' => $e->getMessage()], 404);
        }
    }
}
