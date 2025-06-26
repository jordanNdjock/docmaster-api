<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *   schema="Paiement",
 *   type="object",
 *   required={"id", "transaction_id", "etat", "montant"},
 *   @OA\Property(property="id", type="string", example="uuid-paiement-1"),
 *   @OA\Property(property="transaction_id", type="string", example="uuid-transaction-1"),
 *   @OA\Property(property="etat", type="string", example="SUCCESS"),
 *   @OA\Property(property="montant", type="number", format="float", example=5000.00)
 * )
 */
class PaymentSchema{}