<?php

namespace App\Services;

use App\Models\TypeDocument;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TypeDocumentServices
{

    /**
     * Récupère les TypeDocument actifs, paginés,
     * et renvoie à la fois les données et les méta-données.
     *
     * @param  int      $perPage  Nombre d'éléments par page (défaut 10)
     * @param  int|null $page     Page courante (résolue automatiquement si null)
     * @return array              ['data' => [...], 'meta' => [...]]
     */
    public function getAllTypeDocuments(int $perPage = 10, ?int $page = null): array
    {
        $page = $page ?: Paginator::resolveCurrentPage();

        $paginator = TypeDocument::active()
        ->paginate(
            $perPage, 
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

    public function getTypeDocumentById(string $id)
    {
        return TypeDocument::active()->findOrFail($id);
    }

    public function createTypeDocument(array $data): TypeDocument
    {
        return DB::transaction(function () use ($data){
            $typeDocument = TypeDocument::create([
                'titre' => $data['titre'],
                'libelle' => $data['libelle'],
                'frais' => $data['frais'],
                'recompense' => $data['recompense'],
                'date_expiration' => $data['date_expiration'] ?? null,
                'validite'        => $data['validite'],
                'supprime'        => false,
            ]);
            Log::channel('admin_actions')->info('TypeDocument crée ', [
                'id'           => $typeDocument->id,
                'titre'        => $typeDocument->titre,
                'created_by'   => auth('admin')->user() ? auth('admin')->user()->email : 'unknown',
            ]);  
            return $typeDocument;
        });

    }

    public function updateTypeDocument(string $id, array $data): TypeDocument
    {
        $typeDocument = TypeDocument::active()->findOrFail($id);
        $typeDocument->update([
            'titre' => $data['titre'],
            'libelle' => $data['libelle'],
            'frais' => $data['frais'],
            'recompense' => $data['recompense'],
            'date_expiration' => $data['date_expiration'] ?? null,
            'validite'        => $data['validite'],
        ]);
        Log::channel('admin_actions')->info('TypeDocument mis à jour ', [
            'id'           => $typeDocument->id,
            'titre'        => $typeDocument->titre,
            'updated_by'   => auth('admin')->user() ? auth('admin')->user()->email : 'unknown',
        ]);        
        return $typeDocument;
    }

    public function deleteTypeDocument(string $id): void
    {
        DB::transaction(function () use ($id){
            $typeDocument = TypeDocument::active()->findOrFail($id);
            $typeDocument->update(['supprime' => true]);

            Log::channel('admin_actions')->info('TypeDocument archivé ', [
                'id'           => $typeDocument->id,
                'titre'        => $typeDocument->titre,
                'archived_by'   => auth('admin')->user() ? auth('admin')->user()->email : 'unknown',
            ]);          });
    }

    public function restoreTypeDocument(string $id): void
    {
        DB::transaction(function () use ($id){
            $typeDocument = TypeDocument::inactive()->findOrFail($id);
            $typeDocument->update(['supprime' => false]);
            Log::channel('admin_actions')->info('TypeDocument restauré ', [
                'id'           => $typeDocument->id,
                'titre'        => $typeDocument->titre,
                'restored_by'   => auth('admin')->user() ? auth('admin')->user()->email : 'unknown',
            ]);          });
    }

    public function forceDeleteTypeDocument(string $id): void
    {
        DB::transaction(function () use ($id){
            $typeDocument = TypeDocument::findOrFail($id);
            $typeDocument->forceDelete();
            Log::channel('admin_actions')->info('TypeDocument supprimé ', [
                'id'           => $typeDocument->id,
                'titre'        => $typeDocument->titre,
                'deleted_by'   => auth('admin')->user() ? auth('admin')->user()->email : 'unknown',
            ]);          
        });
    }

    public function getArchivedTypeDocuments(int $perPage = 10, ?int $page = null): array
    {
        $page = $page ?: Paginator::resolveCurrentPage();

        $paginator = TypeDocument::inactive()
        ->paginate(
            $perPage, 
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
}