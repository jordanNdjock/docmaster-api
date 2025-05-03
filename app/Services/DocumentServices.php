<?php

namespace App\Services;

use App\Models\Document;
use App\Traits\ApiResponse;
use Illuminate\Pagination\Paginator;

class DocumentServices
{

    use ApiResponse;

    /**
     * Get all documents with pagination.
     */
    public function getAllDocuments($per_page = 10, ?int $page = null): array
    {
        $page = $page ?: Paginator::resolveCurrentPage();

        $paginator = Document::active()
            ->paginate(
                $per_page,
                ['*'],
                'page',
                $page
            );
        return [
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ];
    }


     /**
     * Get all user documents with pagination.
     */
    public function getAllUserDocuments($per_page = 10, ?int $page = null): array
    {
        $page = $page ?: Paginator::resolveCurrentPage();

        $paginator = Document::active()->user()
            ->paginate(
                $per_page,
                ['*'],
                'page',
                $page
            );
        return [
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ];
    }

    public function createDocument(array $data)
    {
        // Logique pour créer un document
    }

    public function updateDocument(string $id, array $data)
    {
        // Logique pour mettre à jour un document
    }

    public function deleteDocument(string $id)
    {
        // Logique pour supprimer un document
    }

    public function getDocument(string $id)
    {
        // Logique pour récupérer un document
    }
}