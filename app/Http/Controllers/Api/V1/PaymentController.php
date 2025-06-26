<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\PaymentServices;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PaymentController
{
    use ApiResponse;

    public function __construct(
        private PaymentServices $withdrawalServices
    ){}

    /**
     * @OA\Get(
     *   path="/api/paiement",
     *   tags={"Paiements"},
     *   summary="Récupérer la liste des paiements de l'utilisateur connecté",
     *   description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(name="per_page", in="query", required=false, @OA\Schema(type="integer", example=10)),
     *   @OA\Parameter(name="page", in="query", required=false, @OA\Schema(type="integer", example=1)),
     *   @OA\Response(
     *     response=200,
     *     description="Liste paginée des paiements",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", type="object",
     *         @OA\Property(property="paiements", type="array", @OA\Items(ref="#/components/schemas/Paiement")),
     *         @OA\Property(property="meta", type="object", example={"total":5,"current_page":1,"per_page":10})
     *       ),
     *       @OA\Property(property="message", type="string", example="Liste des paiements de l'utilisateur récupérée avec succès.")
     *     )
     *   )
     * )
     */

    public function index(Request $request){
        $per_page = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $payments = $this->withdrawalServices->getAllUserPayments($per_page, $page);
        return $this->sendResponse(
            [
            'paiements' => $payments['data'],
            'meta' => $payments['meta']
            ],
            'Liste des paiements de l\'utilisateur récupérée avec succès.'
        );
    }

    public function indexAdmin(Request $request){
        $per_page = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $payments = $this->withdrawalServices->getAllPayments($per_page, $page);
        return $this->sendResponse(
            [
            'paiements' => $payments['data'],
            'meta' => $payments['meta']
            ],
            'Liste des paiements récupérée avec succès.'
        );
    }
}
