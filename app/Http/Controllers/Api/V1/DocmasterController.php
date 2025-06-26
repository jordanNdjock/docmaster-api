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
    /**
     * @OA\Get(
     *   path="/api/declaration",
     *   tags={"Déclarations"},
     *   summary="Lister toutes les déclarations",
     *   description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   @OA\Response(
     *     response=200,
     *     description="Liste paginée",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", type="object",
     *         @OA\Property(property="declarations", type="array", @OA\Items(ref="#/components/schemas/Docmaster")),
     *         @OA\Property(property="meta", type="object", example={"total": 20, "current_page": 1, "per_page": 10})
     *       ),
     *       @OA\Property(property="message", type="string", example="Liste des déclarations récupérée avec succès.")
     *     )
     *   )
     * )
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
    /**
     * @OA\Get(
     *   path="/api/declaration/{id}",
     *   tags={"Déclarations"},
     *   summary="Afficher une déclaration",
     *   description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *   @OA\Response(
     *     response=200,
     *     description="Déclaration récupérée",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", ref="#/components/schemas/Docmaster"),
     *       @OA\Property(property="message", type="string", example="Déclaration recupérée avec succès.")
     *     )
     *   )
     * )
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
    /**
     * @OA\Delete(
     *   path="/api/declaration/{id}",
     *   tags={"Déclarations"},
     *   summary="Archiver une déclaration",
     *   description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *   @OA\Response(
     *     response=200,
     *     description="Déclaration archivée",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="message", type="string", example="Déclaration archivée avec succès."),
     *       @OA\Property(property="data", type="object", example={})
     *     )
     *   )
     * )
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
    /**
     * @OA\Post(
     *   path="/api/declaration/{id}/restore",
     *   tags={"Déclarations"},
     *   summary="Restaurer une déclaration",
     *   description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *   @OA\Response(
     *     response=200,
     *     description="Déclaration restaurée",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="message", type="string", example="Déclaration restaurée avec succès."),
     *       @OA\Property(property="data", type="object", example={})
     *     )
     *   )
     * )
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
    /**
     * @OA\Delete(
     *   path="/api/declaration/{id}/force-delete",
     *   tags={"Déclarations"},
     *   summary="Suppression définitive d'une déclaration",
     *   description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *   @OA\Response(
     *     response=200,
     *     description="Déclaration supprimée définitivement",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="message", type="string", example="Déclaration supprimée définitivement avec succès."),
     *       @OA\Property(property="data", type="object", example={})
     *     )
     *   )
     * )
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
    /**
     * @OA\Get(
     *   path="/api/declaration/archived",
     *   tags={"Déclarations"},
     *   summary="Lister les déclarations archivées",
     *   description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   @OA\Response(
     *     response=200,
     *     description="Liste des déclarations archivées",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="archived_declarations", type="array", @OA\Items(ref="#/components/schemas/Docmaster")),
     *         @OA\Property(property="meta", type="object")
     *       ),
     *       @OA\Property(property="message", type="string", example="Liste des déclarations supprimées récupérée avec succès.")
     *     )
     *   )
     * )
     */
    public function archived(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $docmasters = $this->docmasterServices->getArchivedDocmasters($perPage, $page);

        return $this->sendResponse(
            [
            'declarations' => $docmasters['data'],
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
    /**
     * @OA\Get(
     *   path="/api/declaration/search",
     *   tags={"Déclarations"},
     *   summary="Rechercher une déclaration par titre",
     *   description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(name="titre", in="query", required=false, @OA\Schema(type="string")),
     *   @OA\Response(
     *     response=200,
     *     description="Résultats de la recherche",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", type="object",
     *         @OA\Property(property="docmasters", type="array", @OA\Items(ref="#/components/schemas/Docmaster")),
     *         @OA\Property(property="meta", type="object")
     *       ),
     *       @OA\Property(property="message", type="string", example="Liste des déclarations récupérée avec succès.")
     *     )
     *   )
     * )
     */
    public function search(Request $request){
        $per_page = $request->query('per_page', 10);
        $page = $request->query('page', 1);
        $titre = $request->query('titre', '');

        $docmasters = $this->docmasterServices->searchByTitle($titre,$per_page, $page);
        return $this->sendResponse(
            [
            'declarations' => $docmasters['data'],
            'meta' => $docmasters['meta']
            ],
            'Liste des déclarations récupérée avec succès.'
        );
    }

    /**
     * declare a document missed/find
     */
    /**
     * @OA\Post(
     *   path="/api/declaration",
     *   tags={"Déclarations"},
     *   summary="Déclarer un document trouvé ou perdu",
     *   description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="type_docmaster", type="string", example="Chercher | Trouver"),
     *       @OA\Property(property="date_action", type="string", example="2024-09-23"),
     *       @OA\Property(property="type_document_id", type="string", example="uuid-type-doc"),
     *       @OA\Property(property="nom_proprietaire", type="string", example="Michel John"),
     *       @OA\Property(property="titre_document", type="string", example="Acte de Naissance"),
     *       @OA\Property(property="date_expiration", type="string", example="2022-08-24"),
     *       @OA\Property(property="nom_trouveur", type="string", example="Jean Dupont"),
     *       @OA\Property(property="tel_trouveur", type="string", example="+237693899890"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Déclaration enregistrée",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", ref="#/components/schemas/Docmaster"),
     *       @OA\Property(property="message", type="string", example="Déclaration créée avec succès.")
     *     )
     *   )
     * )
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
