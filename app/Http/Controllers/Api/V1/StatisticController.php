<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\StatisticServices;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class StatisticController
{
    use ApiResponse;

    public function __construct(
        private StatisticServices $statisticservices 
    ){}

    public function getStats(){
        try {
            $stats = $this->statisticservices->getAllStat();
            return $this->sendResponse(
                ['statistics' => $stats],
            'Statistiques recupérées avec succès.'
            );
        } catch (\Throwable $th) {
            return $this->sendError("Erreur de la l'affichage des stats", [$th->getMessage()],500);
        }
    }
}
