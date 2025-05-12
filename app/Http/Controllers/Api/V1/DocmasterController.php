<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Docmaster\DocmasterRequest;
use App\Services\DocmasterServices;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DocmasterController
{

    use ApiResponse;

    public function __construct(
        protected DocmasterServices $docmasterServices,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $docmasters = $this->docmasterServices->getAllDocmasters($per_page, $page);
        return $this->sendResponse(
            [
            'declarations' => $docmasters['data'],
            'meta' => $docmasters['meta']
            ],
            'Liste des déclarations récupérée avec succès.'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $docmaster = $this->docmasterServices->getDocmasterById($id);
            return $this->sendResponse(
                $docmaster,
                'Déclaration recupérée avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Déclaration non trouvée !', ['error' => $e->getMessage()], 404);
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
            $this->docmasterServices->deleteDocmaster($id);
            return $this->sendResponse(
                [],
                'Déclaration archivée avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Déclaration non trouvée !', ['error' => $e->getMessage()], 404);
        }
    }

     /**
     * Restore the specified resource from storage.
     * @param string $id
     * @return JsonResponse
     */
    public function restore(string $id)
    {
        try {
            $this->docmasterServices->restoreDocmaster($id);
            return $this->sendResponse(
                [],
                'Déclaration restaurée avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Déclaration non trouvée !', ['error' => $e->getMessage()], 404);
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
            $this->docmasterServices->forceDeleteDocmaster($id);
            return $this->sendResponse(
                [],
                'Déclaration supprimée définitivement avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Déclaration non trouvée !', ['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Display a listing of the archived resource.
     */
    public function archived(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $docmasters = $this->docmasterServices->getArchivedDocmasters($perPage, $page);

        return $this->sendResponse(
            [
            'archived_declarations' => $docmasters['data'],
            'meta' => $docmasters['meta']
            ],
            'Liste des déclarations supprimées récupérée avec succès.'
        );
    }

    /**
     * Search for a resource by title.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request){
        $per_page = $request->query('per_page', 10);
        $page = $request->query('page', 1);
        $titre = $request->query('titre', '');

        $docmasters = $this->docmasterServices->searchByTitle($titre,$per_page, $page);
        return $this->sendResponse(
            [
            'docmasters' => $docmasters['data'],
            'meta' => $docmasters['meta']
            ],
            'Liste des déclarations récupérée avec succès.'
        );
    }

    /**
     * declare a document missed/find
     */
    public function store(DocmasterRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $docmaster = $this->docmasterServices->declareDocmaster($validatedData);
            return $this->sendResponse(
                $docmaster,
                'Déclaration créée avec succès.'
            );
        } catch (\Throwable $e) {
            return $this->sendError('Déclaration non trouvée !', ['error' => $e->getMessage()], 404);
        }
    }
}
