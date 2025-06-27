<?php

namespace App\Services;

use App\Models\Abonnement;
use App\Models\AbonnementUser;
use App\Models\Docmaster;
use App\Models\Document;
use App\Models\Paiement;
use App\Models\Retrait;
use App\Models\Transaction;
use App\Models\TypeDocument;
use App\Models\User;

class StatisticServices{
    
    public function getAllStat(){
        return [
            'total_documents' => Document::active()->count(),
            'total_abonnements' => Abonnement::active()->count(),
            'total_types_documents' => TypeDocument::active()->count(),
            'total_abonnements_utilisateurs' => AbonnementUser::count(),
            'total_declarations' => Docmaster::active()->count(),
            'total_utilisateurs' => User::active()->count(),
            'total_paiements' => Paiement::count(),
            'total_transactions' => Transaction::count(),
            'total_retraits' => Retrait::where('etat', true)->count(),
        ];
    }
}