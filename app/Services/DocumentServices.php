<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class DocumentServices
{

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

        $paginator = Document::active()->with('user')
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

    public function getDocumentById(string $id)
    {
        return Document::active()->findOrFail($id);
    }

    public function createDocument(array $data, string $path): Document
    {

        return DB::transaction(function () use ($data, $path){
            $user = auth()->user();

            $abonnementUser = $user->abonnementUser;
            
            $quota = $abonnementUser->abonnement->nombre_docs_par_type;

            $existing = Document::where('type_document_id', $data['type_document_id'])
            ->where('user_id', $user->id)
            ->count();

            if ($existing >= $quota) {
                throw new TooManyRequestsHttpException(
                    null,
                    "Vous avez atteint le quota de {$quota} documents autorisés pour ce type de document."
                );
            }

            $document = Document::create([
                'type_document_id' => $data['type_document_id'],
                'user_id' =>  $user->id,
                'nom_proprietaire' => $data['nom_proprietaire'],
                'titre' => $data['titre'],
                'date_expiration' => $data['date_expiration'] ?? null,
                'fichier_url' => $path,
                'trouve' => false,
                'sauvegarde' => true,
                'signale'    => false,
            ]);
            Log::channel('user_actions')->info('Document crée ', [
                'id'           => $document->id,
                'titre'        => $document->titre,
                'created_by'   =>  $user ?  $user->email : 'unknown',
            ]);  
            return $document;
        });
    }

    public function updateDocument(string $id, array $data, string $path)
    {
        $user = auth()->user();
        $document = Document::active()->findOrFail($id);
        $document->update([
            'type_document_id' => $data['type_document_id'],
            'user_id' =>  $user->id,
            'nom_proprietaire' => $data['nom_proprietaire'],
            'titre' => $data['titre'],
            'fichier_url' => $path,
            'trouve' => false,
            'sauvegarge' => true,
            'signale'    => false,
            'supprime'   => false,
        ]);
        Log::channel('user_actions')->info('Document mis à jour ', [
            'id'           => $document->id,
            'titre'        => $document->titre,
            'updated_by'   =>  $user ?  $user->email : 'unknown',
        ]);        
        return $document;
    }

    public function deleteDocument(string $id)
    {
        DB::transaction(function () use ($id){
            $document = Document::active()->findOrFail($id);
            $document->update(['supprime' => true]);

            Log::channel('user_actions')->info('Document archivé ', [
                'id'           => $document->id,
                'titre'        => $document->titre,
                'archived_by'   => auth()->user() ? auth()->user()->email : 'unknown',
            ]);          
        });
    }

    public function restoreDocument(string $id): void
    {
        DB::transaction(function () use ($id){
            $document = Document::archived()->findOrFail($id);
            $document->update(['supprime' => false]);
            Log::channel('user_actions')->info('Document restauré ', [
                'id'           => $document->id,
                'titre'        => $document->titre,
                'restored_by'   => auth()->user() ? auth()->user()->email : 'unknown',
            ]);          
        });
    }

    public function forceDeleteDocument(string $id): void
    {
        DB::transaction(function () use ($id){
            $document = Document::findOrFail($id);
            $document->forceDelete();
            Log::channel('user_actions')->info('Document supprimé ', [
                'id'           => $document->id,
                'titre'        => $document->titre,
                'deleted_by'   => auth()->user() ? auth()->user()->email : 'unknown',
            ]);          
        });
    }

    public function getArchivedDocuments(int $perPage = 10, ?int $page = null): array
    {
        $page = $page ?: Paginator::resolveCurrentPage();

        $paginator = Document::archived()->user()
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