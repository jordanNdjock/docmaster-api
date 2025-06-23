<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *   schema="Document",
 *   type="object",
 *   required={"id", "user_id", "type_document_id", "nom_proprietaire", "titre", "fichier_url"},
 *   @OA\Property(property="id", type="string", example="uuid-1234"),
 *   @OA\Property(property="user_id", type="string", example="uuid-user-1"),
 *   @OA\Property(property="type_document_id", type="string", example="uuid-type-doc"),
 *   @OA\Property(property="nom_proprietaire", type="string", example="Jean Michel"),
 *   @OA\Property(property="titre", type="string", example="Acte de naissance"),
 *   @OA\Property(property="date_expiration", type="string", format="date", example="2026-12-31"),
 *   @OA\Property(property="fichier_url", type="string", example="documents/acte-naissance.pdf"),
 *   @OA\Property(property="trouve", type="boolean", example=false),
 *   @OA\Property(property="sauvegarde", type="boolean", example=false),
 *   @OA\Property(property="signale", type="boolean", example=false)
 * )
 */
class DocumentSchema{}