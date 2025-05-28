<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Abonnement\AbonnementRequest;
use App\Http\Requests\Transaction\TransactionRequest;
use App\Services\AbonnementServices;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AbonnementController
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponse;
    public function __construct(
        protected AbonnementServices $abonnementServices
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $abonnements = $this->abonnementServices->getAllAbonnements($per_page, $page);
        return $this->sendResponse(
            [
            'abonnements' => $abonnements['data'],
            'meta' => $abonnements['meta']
            ],
            'Liste des abonnements récupérée avec succès.'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AbonnementRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $abonnements = $this->abonnementServices->createAbonnement($validatedData);
            return $this->sendResponse(
                $abonnements,
                'Abonnement créé avec succès.'
            );
        } catch (\Exception $e) {
            return $this->sendError('Erreur lors de la création de l\'abonnement.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $abonnements = $this->abonnementServices->getAbonnementById($id);
            return $this->sendResponse(
                $abonnements,
                'Abonnement recupéré avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Abonnement non trouvé !', ['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AbonnementRequest $request, string $id)
    {
        $validatedData = $request->validated();

        try {
            $abonnements = $this->abonnementServices->updateAbonnement($id, $validatedData);
            return $this->sendResponse(
                $abonnements,
                'Abonnement mis à jour avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Abonnement non trouvé !', ['error' => $e->getMessage()], 404);
        }
        catch (\Exception $e) {
            return $this->sendError('Erreur lors de la mise à jour de l\'abonnement.', [$e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $this->abonnementServices->deleteAbonnement($id);
            return $this->sendResponse(
                [],
                'Abonnement archivé avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Abonnement non trouvé !', ['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Restore the specified resource from storage.
     * @param string $id
     * @return JsonResponse
     */
    public function restore(string $id): JsonResponse
    {
        try {
            $this->abonnementServices->restoreAbonnement($id);
            return $this->sendResponse(
                [],
                'Abonnement restauré avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Abonnement non trouvé !', ['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Force Remove the specified resource from storage
     * @param string $id
     * @return JsonResponse
     */
    public function forceDelete(string $id)
    {
        try {
            $this->abonnementServices->forceDeleteAbonnement($id);
            return $this->sendResponse(
                [],
                'Abonnement supprimé définitivement avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Abonnement non trouvé !', ['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Display a listing of the archived resource.
     */
    public function archived(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $abonnements = $this->abonnementServices->getArchivedAbonnements($perPage, $page);

        return $this->sendResponse(
            [
            'archived_abonnements' => $abonnements['data'],
            'meta' => $abonnements['meta']
            ],
            'Liste des abonnements supprimés récupérée avec succès.'
        );
    }

    /**
     * Subscribe to an abonnement
     */
    public function subscribe(TransactionRequest $request, string $id): JsonResponse
    {
        $validatedData = $request->validated();
        try {
             $abonnement = $this->abonnementServices->subscribeToAbonnement($id, $validatedData);
             $status = $abonnement?->statut;

             if ($status === 'FAILED' || $status === 'PENDING') {
                return $this->sendError(
                    'Erreur lors de la souscription à l\'abonnement.',
                    [$abonnement],
                    402
                );
             }
             
            return $this->sendResponse(
                $abonnement,
                'Abonnement souscrit avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Abonnement non trouvé !', ['error' => $e->getMessage()], 404);
        } 
        catch (\Exception $e) {
            return $this->sendError('Erreur lors de la souscription à l\'abonnement.', ['error' => $e->getMessage()], 500);
        }
    }
}
