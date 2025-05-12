<?php

namespace App\Services;

use App\Models\Docmaster;
use App\Models\Document;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DocmasterServices
{

    public function declareDocmaster(array $data): Docmaster
    {
        return DB::transaction(function () use ($data){
            $user = auth()->user();
            $mode = $data['type_docmaster'];

            $isSearching = $mode === "Chercher";
            $isFinding = $mode === "Trouver";

            if($isSearching)
            {
                $document = Document::create([
                    'user_id' => $user->id,
                    'type_document_id' => $data['type_document_id'],
                    'titre' => $data['titre_document'],
                    'nom_proprietaire' => $data['nom_proprietaire'],
                    'signale' => true,
                ]);
            }
            elseif($isFinding)
            {
                $document = Document::create([
                    'user_id' => $user->id,
                    'type_document_id' => $data['type_document_id'],
                    'titre' => $data['titre_document'],
                    'nom_proprietaire' => $data['nom_proprietaire'],
                    'date_expiration' => $data['date_expiration'] ?? null,
                    'trouve' => true,
                ]);
            }

            $docmaster = Docmaster::create([
                'doc_chercheur_id' => $isSearching ? $user->id : null,
                'doc_trouveur_id' => $isFinding ? $user->id : null,
                'date_action' => $data['date_action'] ?? null,
                'document_id' => $document->id,
                'nom_trouveur' => $isFinding ? $data['nom_trouveur'] : null,
                'tel_trouveur' => $isFinding ? $data['tel_trouveur'] : null,
                'infos_docs' => $isFinding ? $data['infos_docs'] ?? null : null,
                'type_docmaster' => $mode,
                'etat_docmaster' => $isSearching ? 'Perdu' : 'Trouvé',
            ]);

            Log::channel('user_actions')->info('Déclaration créée ', [
                'id'           => $docmaster->id,
                'etat_docmaster' => $docmaster->etat_docmaster,
                'type_docmaster' => $docmaster->type_docmaster,
                'created_by'   => $user ? $user->email : 'unknown',
            ]); 

            return $docmaster;
        });
    }

    public function searchByTitle(string $titre, $per_page = 10, ?string $page = null): array
    {
        // Rechercher les documents par titre
        $paginator = Docmaster::active()
            ->with(['document','chercheur','trouveur'])
            ->whereHas('document', function($q) use ($titre) {
                $q->where('titre', 'like', "%{$titre}%");
            })
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
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total()
                ]
            ];
    }

    public function getAllDocmasters($per_page = 10, ?string $page = null): array
    {
        $page = $page ?: Paginator::resolveCurrentPage();

        $paginator = Docmaster::active()
            ->orderByDesc('created_at')
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
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total()
            ]
        ];
    }

    public function getDocmasterById(string $id): Docmaster
    {
        return Docmaster::active()->findOrFail($id);
    }
    
    public function deleteDocmaster(string $id)
    {
        DB::transaction(function () use ($id){
            $docmaster = Docmaster::active()->findOrFail($id);
            $docmaster->update(['supprime' => true]);

            Log::channel('user_actions')->info('Déclaration archivée ', [
                'id'           => $docmaster->id,
                'archived_by'   => auth()->user() ? auth()->user()->email : 'unknown',
            ]);          
        });
    }

    public function restoreDocmaster(string $id): void
    {
        DB::transaction(function () use ($id){
            $docmaster = Docmaster::archived()->findOrFail($id);
            $docmaster->update(['supprime' => false]);
            Log::channel('user_actions')->info('Déclaration restaurée ', [
                'id'           => $docmaster->id,
                'restored_by'   => auth()->user() ? auth()->user()->email : 'unknown',
            ]);          
        });
    }

    public function forceDeleteDocmaster(string $id): void
    {
        DB::transaction(function () use ($id){
            $user = auth()->user() ?: auth('admin')->user();
            $docmaster = Docmaster::findOrFail($id);
            $docmaster->forceDelete();
            Log::channel($user->nom_famille ? 'user_actions' : 'admin_actions')->info('Déclaration supprimée ', [
                'id'           => $docmaster->id,
                'deleted_by'   => $user ? $user->email : 'unknown',
            ]);          
        });
    }

    public function getArchivedDocmasters(int $perPage = 10, ?int $page = null): array
    {
        $page = $page ?: Paginator::resolveCurrentPage();

        $paginator = Docmaster::archived()
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