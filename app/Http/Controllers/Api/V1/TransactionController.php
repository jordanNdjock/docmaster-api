<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\TransactionServices;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TransactionController{

    use ApiResponse;

    public function __construct(
        private TransactionServices $transactionServices 
    ){}

    public function indexAdmin(Request $request){
        $per_page = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $payments = $this->transactionServices->getAllTransactions($per_page, $page);
        return $this->sendResponse(
            [
            'transactions' => $payments['data'],
            'meta' => $payments['meta']
            ],
            'Liste des transactions récupérée avec succès.'
        );
    }

    /**
     * @OA\Get(
     *     path="/api/transaction",
     *     summary="Récupération des transactions de l'utilisateur connecté",
     *     tags={"Transactions"},
     *     description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *     @OA\Parameter(name="per_page", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="page", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des transactions utilisateur récupérée avec succès.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="transactions", type="array",
     *                     @OA\Items(ref="#/components/schemas/Transaction")
     *                 ),
     *                 @OA\Property(property="meta", type="object")
     *             ),
     *             @OA\Property(property="message", type="string", example="Liste des transactions de l'utilisateur récupérée avec succès.")
     *         )
     *     )
     * )
     */
    public function index(Request $request){
        $per_page = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $payments = $this->transactionServices->getAllUserTransactions($per_page, $page);
        return $this->sendResponse(
            [
            'transactions' => $payments['data'],
            'meta' => $payments['meta']
            ],
            'Liste des transactions de l\'utilisateur récupérée avec succès.'
        );
    }
}