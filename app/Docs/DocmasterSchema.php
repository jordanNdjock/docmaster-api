<?php

namespace App\Docs;

use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *   schema="Docmaster",
 *   type="object",
 *   required={"id", "document_id", "type_docmaster", "etat_docmaster"},
 *   @OA\Property(property="id", type="string", example="uuid-docmaster-1"),
 *   @OA\Property(property="doc_chercheur_id", type="string", example="uuid-user-1"),
 *   @OA\Property(property="doc_trouveur_id", type="string", example="uuid-user-2"),
 *   @OA\Property(property="document_id", type="string", example="uuid-doc"),
 *   @OA\Property(property="type_docmaster", type="string", example="Trouver"),
 *   @OA\Property(property="date_action", type="string", format="date", example="2025-06-01"),
 *   @OA\Property(property="nom_trouveur", type="string", example="Jean Michel"),
 *   @OA\Property(property="infos_docs", type="string", example="Trouvé au carrefour Emia"),
 *   @OA\Property(property="tel_trouveur", type="string", example="+237690000000"),
 *   @OA\Property(property="etat_docmaster", type="string", example="Trouvé"),
 *   @OA\Property(property="nombre_notif", type="integer", example=1),
 *   @OA\Property(property="credit", type="integer", example=100),
 *   @OA\Property(property="debit", type="integer", example=0),
 *   @OA\Property(property="confirm", type="boolean", example=false),
 *   @OA\Property(property="code_confirm", type="string", example="XYZ123"),
 *   @OA\Property(property="supprime", type="boolean", example=false)
 * )
 */

 class DocmasterSchema{}