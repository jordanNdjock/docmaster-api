<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *   schema="User",
 *   title="User",
 *   description="Représentation d'un utilisateur",
 *
 *   @OA\Property(
 *     property="id",
 *     type="string",
 *     format="uuid",
 *     example="550e8400-e29b-41d4-a716-446655440000"
 *   ),
 *   @OA\Property(
 *     property="nom_famille",
 *     type="string",
 *     example="Doe"
 *   ),
 *  @OA\Property(
 *     property="prenom",
 *     type="string",
 *     example="Joseph Johnson"
 *   ),
 *  @OA\Property(
 *     property="nom_utilisateur",
 *     type="string",
 *     example="testuser"
 *   ),
 *  @OA\Property(
 *     property="initial_2_prenom",
 *     type="string",
 *     example="JJ"
 *   ),
 *  @OA\Property(
 *    property="tel",
 *    type="string",
 *    example="237690098998"
 *   ),
 *  @OA\Property(
 *    property="date_naissance",
 *    type="string",
 *    format="date",
 *    example="2000-01-01"
 *   ),
 *  @OA\Property(
 *    property="infos_paiement",
 *    type="string",
 *    example="Test payment info"
 *   ),
 *  @OA\Property(
 *    property="localisation",
 *    type="string",
 *    example="Test location"
 *   ),
 *   @OA\Property(
 *     property="email",
 *     type="string",
 *     format="email",
 *     example="user@example.com"
 *   ),
 *   @OA\Property(
 *     property="code_invitation",
 *     type="string",
 *     example="TESTCODE"
 *   ),
 *   @OA\Property(
 *     property="solde",
 *     type="number",
 *     example=100
 *   ),
 *   @OA\Property(
 *     property="supprime",
 *     type="boolean",
 *     example=false
 *   ),
 *   @OA\Property(
 *     property="created_at",
 *     type="string",
 *     format="date-time",
 *     example="2025-04-30T08:00:00Z"
 *   ),
 *   @OA\Property(
 *     property="updated_at",
 *     type="string",
 *     format="date-time",
 *     example="2025-04-30T08:00:00Z"
 *   )
 * )
 */
class UserSchema {}
