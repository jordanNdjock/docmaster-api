<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\TypeDocument\TypeDocumentRequest;
use App\Services\TypeDocumentServices;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TypeDocumentController
{
    use ApiResponse;

    public function __construct(
        protected TypeDocumentServices $typeDocumentServices
    ){}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $typeDocuments = $this->typeDocumentServices->getAllTypeDocuments($perPage, $page);

        return $this->sendResponse(
            [
            'type_documents' => $typeDocuments['data'],
            'meta' => $typeDocuments['meta']
            ],
            'Liste des types de documents récupérée avec succès.'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TypeDocumentRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $typeDocument = $this->typeDocumentServices->createTypeDocument($validatedData);
            return $this->sendResponse(
                $typeDocument,
                'Type de document créé avec succès.'
            );
        } catch (\Exception $e) {
            return $this->sendError('Erreur lors de la création du type de document.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $typeDoc = $this->typeDocumentServices->getTypeDocumentById($id);
            return $this->sendResponse(
                $typeDoc,
                'Type de document recupéré avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Type de document non trouvé !', ['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TypeDocumentRequest $request, string $id)
    {
        $validatedData = $request->validated();

        try {
            $typeDocument = $this->typeDocumentServices->updateTypeDocument($id, $validatedData);
            return $this->sendResponse(
                $typeDocument,
                'Type de document mis à jour avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Type de document non trouvé !', ['error' => $e->getMessage()], 404);
        }
        catch (\Exception $e) {
            return $this->sendError('Erreur lors de la mise à jour du type de document.', [$e->getMessage()], 500);
        }
    }

    /**
     * Soft Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $this->typeDocumentServices->deleteTypeDocument($id);
            return $this->sendResponse(
                [],
                'Type de document archivé avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Type de document non trouvé !', ['error' => $e->getMessage()], 404);
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
            $this->typeDocumentServices->restoreTypeDocument($id);
            return $this->sendResponse(
                [],
                'Type de document restauré avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Type de document non trouvé !', ['error' => $e->getMessage()], 404);
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
            $this->typeDocumentServices->forceDeleteTypeDocument($id);
            return $this->sendResponse(
                [],
                'Type de document supprimé définitivement avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Type de document non trouvé !', ['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Display a listing of the archived resource.
     */
    public function archived(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $typeDocuments = $this->typeDocumentServices->getArchivedTypeDocuments($perPage, $page);

        return $this->sendResponse(
            [
            'archived_type_documents' => $typeDocuments['data'],
            'meta' => $typeDocuments['meta']
            ],
            'Liste des types de documents supprimés récupérée avec succès.'
        );
    }
}
