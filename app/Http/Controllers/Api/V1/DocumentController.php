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
    /**
     * @OA\Get(
     *   path="/api/document",
     *   tags={"Documents"},
     *   summary="Liste des documents de l'utilisateur authentifié",
     *   description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(name="per_page", in="query", required=false, @OA\Schema(type="integer")),
     *   @OA\Parameter(name="page", in="query", required=false, @OA\Schema(type="integer")),
     *   @OA\Response(
     *     response=200,
     *     description="Liste paginée",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(
     *         property="data", type="object",
     *         @OA\Property(property="user_documents", type="array", @OA\Items(ref="#/components/schemas/Document")),
     *         @OA\Property(property="meta", type="object", example={"total": 20, "current_page": 1, "per_page": 10})
     *       ),
     *       @OA\Property(property="message", type="string", example="Liste des documents de l'utilisateur récupérée avec succès.")
     *     )
     *   )
     * )
     */

    public function index(Request $request ): JsonResponse
    {
        $per_page = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $documents = $this->documentServices->getAllUserDocuments($per_page, $page);
        return $this->sendResponse(
            [
            'documents' => $documents['data'],
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

     /**
     * @OA\Post(
     *   path="/api/document",
     *   tags={"Documents"},
     *   summary="Créer un document",
     *   description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *         required={"type_document_id", "nom_proprietaire", "titre", "fichier_document"},
     *         @OA\Property(property="type_document_id", type="string", example="uuid-type-doc"),
     *         @OA\Property(property="nom_proprietaire", type="string", example="Jean Michel"),
     *         @OA\Property(property="titre", type="string", example="Permis de conduire"),
     *         @OA\Property(property="date_expiration", type="string", format="date", example="2025-12-31"),
     *         @OA\Property(property="fichier_document", type="string", format="binary")
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Document créé",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", ref="#/components/schemas/Document"),
     *       @OA\Property(property="message", type="string", example="document créé avec succès.")
     *     )
     *   )
     * )
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
    /**
     * @OA\Get(
     *   path="/api/document/{id}",
     *   tags={"Documents"},
     *   summary="Afficher un document",
     *   description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *   @OA\Response(
     *     response=200,
     *     description="Document récupéré",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", ref="#/components/schemas/Document"),
     *       @OA\Property(property="message", type="string", example="Document récupéré avec succès.")
     *     )
     *   ),
     *   @OA\Response(response=404, description="Document non trouvé")
     * )
     */

    public function show(string $id)
    {
        try {
            $document = $this->documentServices->getDocumentById($id);
            return $this->sendResponse(
                $document,
                'Document recupéré avec succès.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Document non trouvé !', ['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *   path="/api/document/{id}",
     *   tags={"Documents"},
     *   summary="Mettre à jour un document",
     *   description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *         required={"type_document_id", "nom_proprietaire", "titre", "fichier_document"},
     *         @OA\Property(property="type_document_id", type="string", example="uuid-type-doc"),
     *         @OA\Property(property="nom_proprietaire", type="string", example="Jean Michel"),
     *         @OA\Property(property="titre", type="string", example="Permis de conduire"),
     *         @OA\Property(property="date_expiration", type="string", format="date", example="2025-12-31"),
     *         @OA\Property(property="fichier_document", type="string", format="binary")
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Document modifié",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", ref="#/components/schemas/Document"),
     *       @OA\Property(property="message", type="string", example="document modifié avec succès.")
     *     )
     *   )
     * )
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
    /**
     * @OA\Delete(
     *   path="/api/document/{id}",
     *   tags={"Documents"},
     *   summary="Archiver un document",
     *   description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *   @OA\Response(
     *     response=200,
     *     description="Document archivé avec succès",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", type="object", example={}),
     *       @OA\Property(property="message", type="string", example="Document archivé avec succès.")
     *     )
     *   )
     * )
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
    /**
     * @OA\Post(
     *   path="/api/document/{id}/restore",
     *   tags={"Documents"},
     *   summary="Restaurer un document",
     *   description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *   @OA\Response(
     *     response=200,
     *     description="Document restauré",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", type="object", example={}),
     *       @OA\Property(property="message", type="string", example="Document restauré avec succès.")
     *     )
     *   )
     * )
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
    /**
     * @OA\Delete(
     *   path="/api/document/{id}/force-delete",
     *   tags={"Documents"},
     *   summary="Supprimer définitivement un document",
     *   description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *   @OA\Response(
     *     response=200,
     *     description="Document supprimé définitivement",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", type="object", example={}),
     *       @OA\Property(property="message", type="string", example="Document supprimé définitivement avec succès.")
     *     )
     *   )
     * )
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
    /**
     * @OA\Get(
     *   path="/api/document/archived",
     *   tags={"Documents"},
     *   summary="Lister les documents archivés",
     *   description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   @OA\Response(
     *     response=200,
     *     description="Liste des documents archivés",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="archived_documents", type="array", @OA\Items(ref="#/components/schemas/Document")),
     *         @OA\Property(property="meta", type="object")
     *       ),
     *       @OA\Property(property="message", type="string", example="Liste des documents supprimés récupérée avec succès.")
     *     )
     *   )
     * )
     */
    public function archived(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $documents = $this->documentServices->getArchiveddocuments($perPage, $page);

        return $this->sendResponse(
            [
            'documents' => $documents['data'],
            'meta' => $documents['meta']
            ],
            'Liste des documents supprimés récupérée avec succès.'
        );
    }
}
