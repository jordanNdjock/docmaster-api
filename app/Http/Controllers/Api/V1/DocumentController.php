<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Document\DocumentRequest;
use App\Services\DocumentServices;
use App\Services\FileServices;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DocumentController
{
    use ApiResponse;

    public function __construct(
        protected DocumentServices $documentServices,
        protected FileServices $fileServices
    ){}
    /**
     * Display a listing of the resource.
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
     * Display a listing of the resource for the authenticated user.
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
            $destinationPath = 'documents_'.auth()->user()->nom_utilisateur;
            $path = $this->fileServices->storeFile($request->file('fichier_document'), $destinationPath);
            $document = $this->documentServices->createDocument($validatedData, $path);
            return $this->sendResponse(
                $document,
                'document créé avec succès.'
            );
        } catch (\Exception $e) {
            return $this->sendError('Erreur lors de la création du document.', [], 500);
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
            return $this->sendError('Document non trouvé !', [], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DocumentRequest $request, string $id)
    {
        $validatedData = $request->validated();

        try {
            $destinationPath = 'documents_'.auth()->user()->nom_utilisateur;
            $path = $this->fileServices->updateFile($request->file('fichier_document'), $destinationPath, $id);
            $document = $this->documentServices->updateDocument($id, $validatedData, $path);
            return $this->sendResponse(
                $document,
                'document modifié avec succès.'
            );
        } catch (\Exception $e) {
            return $this->sendError('Erreur lors de la modification du document.', [], 500);
        } catch (ModelNotFoundException $e){
            return $this->sendError('Document non trouvé !', [], 404);
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
                'Document supprimé avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Document non trouvé !', [], 404);
        }
    }
}
