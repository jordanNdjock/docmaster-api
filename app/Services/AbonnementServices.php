<?php

namespace App\Services;

use App\Models\Abonnement;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AbonnementServices
{
    /**
     * Get all documents with pagination.
     */
    public function getAllAbonnements($per_page = 10, ?int $page = null): array
    {
        $page = $page ?: Paginator::resolveCurrentPage();

        $paginator = Abonnement::active()
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
     * Get document by id.
    */
    public function getAbonnementById(string $id)
    {
        return Abonnement::active()->findOrFail($id);
    }

    /**
     * Create a new document.
     */
    public function createAbonnement(array $data): Abonnement
    {
        return DB::transaction(function () use ($data){
            $abonnement = Abonnement::create([
                'titre' => $data['titre'],
                'nombre_docs_par_type' => $data['nombre_docs_par_type'],
                'date_debut' => $data['date_debut'],
                'date_expiration' => $data['date_expiration'],
                'montant'        => $data['montant'],
                'supprime'        => false,
            ]);
            Log::channel('admin_actions')->info('Abonnement crée ', [
                'id'           => $abonnement->id,
                'titre'        => $abonnement->titre,
                'created_by'   => auth('admin')->user() ? auth('admin')->user()->email : 'unknown',
            ]);  
            return $abonnement;
        });
    }
    
    /**
     * Update an abonnement.
     * @param string $id
     * @param array $data
     * @return Abonnement
     */
    public function updateAbonnement(string $id, array $data): Abonnement
    {
        $abonnement = Abonnement::active()->findOrFail($id);
        $abonnement->update([
            'titre' => $data['titre'],
            'nombre_docs_par_type' => $data['nombre_docs_par_type'],
            'date_debut' => $data['date_debut'],
            'date_expiration' => $data['date_expiration'],
            'montant'        => $data['montant'],
            'supprime'        => false,
        ]);
        Log::channel('admin_actions')->info('Abonnement mis à jour ', [
            'id'           => $abonnement->id,
            'titre'        => $abonnement->titre,
            'updated_by'   => auth('admin')->user() ? auth('admin')->user()->email : 'unknown',
        ]);        
        return $abonnement;
    }

    /**
     * Archive an abonnement.
     * @param string $id
     * @return void
     */
    public function deleteAbonnement(string $id): void
    {
        DB::transaction(function () use ($id){
            $abonnement = Abonnement::active()->findOrFail($id);
            $abonnement->update(['supprime' => true]);

            Log::channel('admin_actions')->info('Abonnement archivé ', [
                'id'           => $abonnement->id,
                'titre'        => $abonnement->titre,
                'archived_by'   => auth('admin')->user() ? auth('admin')->user()->email : 'unknown',
            ]);          
        });
    }

    /**
     * Restore an archived abonnement.
     * @param string $id
     * @return void
     */
    public function restoreAbonnement(string $id): void
    {
        DB::transaction(function () use ($id){
            $abonnement = Abonnement::archived()->findOrFail($id);
            $abonnement->update(['supprime' => false]);
            Log::channel('admin_actions')->info('Abonnement restauré ', [
                'id'           => $abonnement->id,
                'titre'        => $abonnement->titre,
                'restored_by'   => auth('admin')->user() ? auth('admin')->user()->email : 'unknown',
            ]);          
        });
    }

    /**
     * Permanently delete an abonnement.
     * @param string $id
     * @return void
     */
    public function forceDeleteAbonnement(string $id): void
    {
        DB::transaction(function () use ($id){
            $abonnement = Abonnement::findOrFail($id);
            $abonnement->forceDelete();
            Log::channel('admin_actions')->info('Abonnement supprimé ', [
                'id'           => $abonnement->id,
                'titre'        => $abonnement->titre,
                'deleted_by'   => auth('admin')->user() ? auth('admin')->user()->email : 'unknown',
            ]);          
        });
    }

    /**
     * Get all archived documents with pagination.
     */
    public function getArchivedAbonnements(int $perPage = 10, ?int $page = null): array
    {
        $page = $page ?: Paginator::resolveCurrentPage();

        $paginator = Abonnement::archived()
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

    /**
     * Subscribe to an abonnement.
     * @param string $id
     * @return void
     */
    public function subscribeToAbonnement(string $id): void
    {
        //
    }
}