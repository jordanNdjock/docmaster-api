<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthServices;
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
                    'user'         => $user,
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



    public function register(RegisterRequest $request){
        $credentials = $request->validated();
        try {
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
