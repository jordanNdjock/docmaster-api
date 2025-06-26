<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthServices;
use App\Services\UserFileServices;
use App\Traits\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected AuthServices $authServices,
        protected UserFileServices $userFileServices,
    ) {}

   /**
     * @OA\Post(
     *   path="/api/auth/login",
     *   tags={"Authentification"},
     *   summary="Authentification d'un utilisateur et retour d'un accès token",
     *
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="nom_utilisateur", type="string", example="testuser"),
     *       @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *       @OA\Property(property="mdp",   type="string", example="secret123")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Connexion réussie",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(
     *         property="data", type="object",
     *         @OA\Property(
     *           property="user", 
     *           type="object",
     *           description="Détails de l'utilisateur authentifié",
     *           ref="#/components/schemas/User"
     *         ),
     *         @OA\Property(property="access_token", type="string", example="1|gChmEahrYbZLpZMOdxCmokA0ntqqtwaXTokCkeHld7172a26")
     *       ),
     *       @OA\Property(property="message", type="string", example="Connexion réussie.")
     *     )
     *   ),
     *
     *   @OA\Response(response=401, description="Identifiants invalides"),
     *   @OA\Response(response=500, description="Erreur interne du serveur")
     * )
     */

    public function login(LoginRequest $request): JsonResponse{
        $credentials = $request->validated();
        try {

            ['user' => $user, 'token' => $plainTextToken] =
            $this->authServices->authenticate(
                $credentials['email'],
                $credentials['mdp']
            );

            return $this->sendResponse(
                [
                    'utilisateur'         => $user,
                    'access_token' => $plainTextToken,
                ],
                'Connexion réussie.',
            );

        } catch (AuthenticationException $e) {
            return $this->sendError(
                $e->getMessage(),
                [],
                401
            );
        } catch (\Throwable $th) {
            return $this->sendError('Erreur interne lors de la connexion.', ['error' => $th->getMessage()], 500);
        }
    }


    /**
     * @OA\Post(
     *   path="/api/auth/register",
     *   tags={"Authentification"},
     *   summary="Enregistrement d'un nouvel utilisateur",
     *
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"prenom", "nom_famille", "nom_utilisateur", "email", "mdp", "tel", "date_naissance"},
     *       @OA\Property(property="prenom", type="string", example="Jean Michel"),
     *       @OA\Property(property="nom_famille", type="string", example="Atangana"),
     *       @OA\Property(property="nom_utilisateur", type="string", example="jmatango"),
     *       @OA\Property(property="email", type="string", format="email", example="jma@example.com"),
     *       @OA\Property(property="mdp", type="string", example="secret123"),
     *       @OA\Property(property="tel", type="string", example="+237690112233"),
     *       @OA\Property(property="date_naissance", type="string", format="date", example="1995-05-21"),
     *       @OA\Property(property="localisation", type="string", example="Yaoundé"),
     *       @OA\Property(property="infos_paiement", type="string", example="Mobile Money"),
     *       @OA\Property(property="photo_url", type="string", format="binary", description="Fichier image (optionnel)")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=201,
     *     description="Utilisateur enregistré avec succès",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="user", ref="#/components/schemas/User"),
     *         @OA\Property(property="access_token", type="string", example="1|gChmEahrYbZLpZMOdxCmokA0ntqqtwaXTokCkeHld7172a26"),
     *       ),
     *
     *       @OA\Property(property="message", type="string", example="Compte créé avec succès.")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=422,
     *     description="Données de validation incorrectes",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=false),
     *       @OA\Property(property="message", type="string", example="Les données fournies sont invalides.")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=500,
     *     description="Erreur interne du serveur",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=false),
     *       @OA\Property(property="message", type="string", example="Erreur interne lors de la création du compte.")
     *     )
     *   )
     * )
     */

    public function register(RegisterRequest $request){
        $credentials = $request->validated();
        try {
            if($request->hasFile('photo_url'))
                $path = $this->userFileServices->storeFile($request->file('photo_url'));
            
            $this->authServices->register($credentials, $path);
        } catch (\Throwable $th) {
            return $this->sendError('Erreur interne lors de la création du compte.', ['error' => $th->getMessage()], 500);
        }
    }



    /**
     * @OA\Post(
     *   path="/api/auth/logout",
     *   tags={"Authentification"},
     *   summary="Déconnexion de l'utilisateur",
     *   description="Nécessite un token Bearer dans l'en-tête Authorization",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *
     *   @OA\Response(
     *     response=200,
     *     description="Déconnexion réussie",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", type="object", example={}),
     *       @OA\Property(property="message", type="string", example="Déconnexion réussie !")
     *     )
     *   ),
     *   @OA\Response(response=401, description="Non authentifié"),
     *   @OA\Response(response=500, description="Erreur interne du serveur")
     * )
     */

     public function logout(Request $request): JsonResponse
     {
        try {
            $this->authServices->logout($request->user());

            return $this->sendResponse(
                null,
                'Déconnexion réussie !',
                200
            );

        } catch (AuthenticationException $e) {
            return $this->sendError(
                $e->getMessage(),
                [],
                401
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                'Erreur lors de la déconnexion.',
                ['error' => $th->getMessage()],
                500
            );
        }
     }
     
}
