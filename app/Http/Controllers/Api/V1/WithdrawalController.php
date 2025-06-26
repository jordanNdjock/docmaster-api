<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Transaction\TransactionRequest;
use App\Services\WithdrawalServices;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class WithdrawalController
{
    use ApiResponse;

    public function __construct(
        private WithdrawalServices $withdrawalServices
    ){}

    /**
     * @OA\Get(
     *   path="/api/retrait",
     *   tags={"Retraits"},
     *   summary="Lister les retraits de l'utilisateur connecté",
     *   description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   @OA\Response(
     *     response=200,
     *     description="Liste paginée des retraits",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", type="object",
     *         @OA\Property(property="retraits", type="array", @OA\Items(ref="#/components/schemas/Retrait")),
     *         @OA\Property(property="meta", type="object")
     *       ),
     *       @OA\Property(property="message", type="string", example="Liste des retraits de l'utilisateur récupérée avec succès.")
     *     )
     *   )
     * )
     */
    public function index(Request $request){
        $per_page = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $withdrawals = $this->withdrawalServices->getAllUserWithdrawals($per_page, $page);
        return $this->sendResponse(
            [
            'retraits' => $withdrawals['data'],
            'meta' => $withdrawals['meta']
            ],
            'Liste des retraits de l\'utilisateur récupérée avec succès.'
        );
    }

    public function indexAdmin(Request $request){
        $per_page = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $withdrawals = $this->withdrawalServices->getAllWithdrawals($per_page, $page);
        return $this->sendResponse(
            [
            'retraits' => $withdrawals['data'],
            'meta' => $withdrawals['meta']
            ],
            'Liste des retraits récupérée avec succès.'
        );
    }


    /**
     * @OA\Post(
     *   path="/api/retrait",
     *   tags={"Retraits"},
     *   summary="Effectuer un retrait",
     *   description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="tel", type="string", example="+237690000000"),
     *       @OA\Property(property="montant", type="number", format="float", example=2500),
     *       @OA\Property(property="transactionable_type", type="string", example="retrait")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Retrait réussi",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", ref="#/components/schemas/Retrait"),
     *       @OA\Property(property="message", type="string", example="Retrait effectué avec succès.")
     *     )
     *   ),
     *   @OA\Response(response=402, description="Erreur lors du retrait"),
     *   @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function withdraw(TransactionRequest $request){
        $validatedData = $request->validated();
        try {
            $retrait = $this->withdrawalServices->makeWithdrawal($validatedData);
            $status = $retrait?->statut;

             if ($status === 'FAILED' || $status === 'PENDING') {
                return $this->sendError(
                    'Erreur lors du retrait.',
                    [$retrait],
                    402
                );
             }
             
            return $this->sendResponse(
                $retrait,
                'Retrait effectué avec succès.'
            );
        } catch (\Exception $e) {
            return $this->sendError('Erreur lors du retrait.', ['error' => $e->getMessage()], 500);
        }
    }
}
