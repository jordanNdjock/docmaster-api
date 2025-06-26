<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *   schema="Retrait",
 *   type="object",
 *   required={"id", "user_id", "tel", "montant", "etat"},
 *   @OA\Property(property="id", type="string", example="uuid-retrait-1"),
 *   @OA\Property(property="user_id", type="string", example="uuid-user-1"),
 *   @OA\Property(property="etat", type="string", example="True"),
 *   @OA\Property(property="tel", type="string", example="237690387000"),
 *   @OA\Property(property="montant", type="number", format="float", example=1500),
 *   @OA\Property(property="date", type="string", format="date-time", example="2025-06-21T10:00:00Z")
 * )
 */

 class WithdrawalSchema {}