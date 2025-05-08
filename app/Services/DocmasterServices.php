<?php

namespace App\Services;

use App\Models\Docmaster;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DocmasterServices
{

    function __construct(
        protected DocumentServices $documentServices,
        protected DocumentFileServices $documentFileServices,
    ) {}

    public function declareDocmaster(array $data): array
    {
        return DB::transaction(function () use ($data){
            $user = auth()->user();

            if(!$data['document_id']){
                $path = $this->documentFileServices->storeFile($data['fichier_url'], "documents_{$user->nom_utilisateur}");
                $document = $this->documentServices->createDocument($data, $path);
                $data['document_id'] = $document->id;
            }

            $docmaster = Docmaster::create([
                'document_id' => $data['document_id'],
                'chercheur_id' => $data['chercheur_id'],
                'trouveur_id' => $data['trouveur_id'],
                'date_declaration' => now(),
                'user_id' => $user->id,
                'type_docmaster' => $data['type_docmaster'],
                'etat_docmaster' => $data['etat_docmaster'],
                'credit' => $data['credit'],
                'debit' => $data['debit'],
            ]);

            Log::channel('user_actions')->info('Docmaster(déclaration) créé ', [
                'id'           => $docmaster->id,
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

            Log::channel('user_actions')->info('Docmaster(déclaration) archivé ', [
                'id'           => $docmaster->id,
                'archived_by'   => auth('admin')->user() ? auth('admin')->user()->email : 'unknown',
            ]);          
        });
    }

    public function restoreDocmaster(string $id): void
    {
        DB::transaction(function () use ($id){
            $docmaster = Docmaster::archived()->findOrFail($id);
            $docmaster->update(['supprime' => false]);
            Log::channel('user_actions')->info('Docmaster(déclaration) restauré ', [
                'id'           => $docmaster->id,
                'restored_by'   => auth('admin')->user() ? auth('admin')->user()->email : 'unknown',
            ]);          
        });
    }

    public function forceDeleteDocmaster(string $id): void
    {
        DB::transaction(function () use ($id){
            $docmaster = Docmaster::findOrFail($id);
            $docmaster->forceDelete();
            Log::channel('user_actions')->info('Document supprimé ', [
                'id'           => $docmaster->id,
                'deleted_by'   => auth('admin')->user() ? auth('admin')->user()->email : 'unknown',
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