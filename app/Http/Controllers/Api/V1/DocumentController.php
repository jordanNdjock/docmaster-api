<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Document\DocumentRequest;
use App\Services\AbonnementServices;
use App\Services\DocumentFileServices;
use App\Services\DocumentServices;
use App\Traits\ApiResponse;
use GuzzleHttp\Exception\TooManyRedirectsException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DocumentController
{
    use ApiResponse;

    public function __construct(
        protected DocumentServices $documentServices,
        protected DocumentFileServices $documentFileServices,
        protected AbonnementServices $abonnementServices
    ){}
    /**
     * Display a listing of the resource for the authenticated user.
     */
    public function index(Request $request ): JsonResponse
    {
        $per_page = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $documents = $this->documentServices->getAllUserDocuments($per_page, $page);
        return $this->sendResponse(
            [
            'user_documents' => $documents['data'],
            'meta' => $documents['meta']
            ],
            'Liste des documents de l\'utilisateur récupérée avec succès.'
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function indexAdmin(Request $request): JsonResponse
    {
        $per_page = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $documents = $this->documentServices->getAllDocuments($per_page, $page);
        return $this->sendResponse(
            [
            'documents' => $documents['data'],
            'meta' => $documents['meta']
            ],
            'Liste des documents récupérée avec succès.'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DocumentRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $this->abonnementServices->verifyUserAbonnement();
            $path = $this->documentFileServices->storeFile($request->file('fichier_document'));
            $document = $this->documentServices->createDocument($validatedData, $path);
            return $this->sendResponse(
                $document,
                'document créé avec succès.'
            );
        } catch (\Throwable $th) {
            return $this->sendError('Erreur lors de la création du document.', ['error' => $th->getMessage()], 500);
        }catch (TooManyRedirectsException $th) {
            return $this->sendError('Limite d’enregistrement de documents atteinte.', ['error' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $typeDoc = $this->documentServices->getDocumentById($id);
            return $this->sendResponse(
                $typeDoc,
                'Document recupéré avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Document non trouvé !', ['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DocumentRequest $request, string $id)
    {
        $validatedData = $request->validated();

        try {
            $path = $this->documentFileServices->updateFile($request->file('fichier_document'), $id);
            $document = $this->documentServices->updateDocument($id, $validatedData, $path);
            return $this->sendResponse(
                $document,
                'document modifié avec succès.'
            );
        } catch (\Exception $e) {
            return $this->sendError('Erreur lors de la modification du document.', [], 500);
        } catch (ModelNotFoundException $e){
            return $this->sendError('Document non trouvé !', ['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $this->documentServices->deleteDocument($id);
            return $this->sendResponse(
                [],
                'Document archivé avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Document non trouvé !', ['error' => $e->getMessage()], 404);
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
            $this->documentServices->restoreDocument($id);
            return $this->sendResponse(
                [],
                'Document restauré avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Document non trouvé !', ['error' => $e->getMessage()], 404);
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
            $this->documentServices->forceDeleteDocument($id);
            $this->documentFileServices->deleteFile($id);
            return $this->sendResponse(
                [],
                'Document supprimé définitivement avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Document non trouvé !', ['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Display a listing of the archived resource.
     */
    public function archived(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $documents = $this->documentServices->getArchiveddocuments($perPage, $page);

        return $this->sendResponse(
            [
            'archived_documents' => $documents['data'],
            'meta' => $documents['meta']
            ],
            'Liste des documents supprimés récupérée avec succès.'
        );
    }
}
