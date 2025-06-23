<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *   schema="TransactionAbonnement",
 *   type="object",
 *   @OA\Property(property="id", type="string", example="uuid-transaction-1"),
 *   @OA\Property(property="user_id", type="string", example="uuid-user-1"),
 *   @OA\Property(property="transactionable_id", type="string", example="uuid-obj"),
 *   @OA\Property(property="transactionable_type", type="string", example="App\Models\Abonnement"),
 *   @OA\Property(property="statut", type="string", example="SUCCESS"),
 *   @OA\Property(property="montant", type="number", example=10000),
 *   @OA\Property(property="identifiant", type="string", example="TXN-2024-0001"),
 *   @OA\Property(property="supprime", type="boolean", example=false)
 * )
 */
class TransactionAbonnementSchema{}