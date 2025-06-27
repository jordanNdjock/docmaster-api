<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Transaction",
 *     type="object",
 *     title="Transaction",
 *     @OA\Property(property="id", type="string", example="uuid-tr_01ab23"),
 *     @OA\Property(property="user_id", type="string", example="uuid-user-1"),
 *     @OA\Property(property="transactionable_id", type="integer", example="uuid-transactionable-1"),
 *     @OA\Property(property="transactionable_type", type="string", example="App\Models\Retrait"),
 *     @OA\Property(property="statut", type="string", example="SUCCESS"),
 *     @OA\Property(property="montant", type="number", format="float", example=2500),
 *     @OA\Property(property="identifiant", type="string", example="TXN_0012421412"),
 * )
 */
class TransactionShema{}