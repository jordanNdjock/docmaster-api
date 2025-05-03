<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\DocumentServices;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DocumentController
{
    use ApiResponse;

    public function __construct(
        protected DocumentServices $documentServices
    ){}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request ): JsonResponse
    {
        $per_page = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $documents = $this->documentServices->getAllDocuments($per_page, $page);
        return $this->sendResponse(
            [
            'data' => $documents['data'],
            'meta' => $documents['meta']
            ],
            'Liste des documents récupérée avec succès.'
        );
    }

    /**
     * Display a listing of the resource for the authenticated user.
     */
    public function indexUser(Request $request): JsonResponse
    {
        $per_page = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $documents = $this->documentServices->getAllUserDocuments($per_page, $page);
        return $this->sendResponse(
            [
            'data' => $documents['data'],
            'meta' => $documents['meta']
            ],
            'Liste des documents de l\'utilisateur récupérée avec succès.'
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
        //
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
        //
    }
}
