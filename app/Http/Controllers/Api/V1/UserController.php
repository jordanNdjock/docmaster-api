<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Utilisateur\UserUpdateRequest;
use App\Models\User;
use App\Services\UserFileServices;
use App\Services\UserServices;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserController
{
    use ApiResponse;

    public function __construct(
        protected UserServices $userServices,
        protected UserFileServices $userFileServices,
    ){}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $users = $this->userServices->getAllUsers($per_page, $page);
        return $this->sendResponse(
            [
            'utilisateurs' => $users['data'],
            'meta' => $users['meta']
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
    public function update(UserUpdateRequest $request, string $id)
    {
        $validated = $request->validated();
        try {
            if($request->hasFile('photo_url')){
                $path = $this->userFileServices->updateFile($request->file('photo_url'), $id);
            }else{
                $user = User::findOrFail($id);
                $path = $user->photo_url ?? null;
            }
            $user = $this->userServices->updateUser($id, $validated, $path);
            return $this->sendResponse(
                $user,
                'Utilisateur mis à jour avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Utilisateur non trouvé !', ['error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return $this->sendError('Erreur lors de la mise à jour du type de document.', [$e->getMessage()], 500);
        }
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

    /**
     * Summary of blocked
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function blocked(string $id){
        try {
            $this->userServices->blockUser($id);
            return $this->sendResponse(
                [],
                'Utilisateur bloqué avec succès.'
            );
        } catch (ModelNotFoundException $th) {
            return $this->sendError('Utilisateur non trouvé !', ['error' => $th->getMessage()], 404);
        }
    }

    /**
     * Summary of restore
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore(string  $id){
        try {
            $this->userServices->restoreUser($id);
            return $this->sendResponse(
                [],
                'Utilisateur restauré avec succès.'
            );
        } catch (ModelNotFoundException $th) {
            return $this->sendError('Utilisateur non trouvé !', ['error' => $th->getMessage()], 404);
        }
    }
}
