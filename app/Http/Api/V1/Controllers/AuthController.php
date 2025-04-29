<?php

namespace App\Http\Api\V1\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA; 

class AuthController extends Controller
{
    use ApiResponse;

      /**
     * @OA\Post(
     *   path="/api/auth/login",
     *   tags={"Authentification"},
     *   summary="Authentification d'un utilisateur",
     *
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="email",    type="string", format="email", example="user@example.com"),
     *       @OA\Property(property="password", type="string", example="secret123")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Connexion réussie",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1Qi..."),
     *     )
     *   ),
     *
     *   @OA\Response(response=401, description="Identifiants invalides"),
     *   @OA\Response(response=500, description="Erreur serveur")
     * )
     */

    public function login(LoginRequest $request): JsonResponse{
        $credentials = $request->validated();
        try {

            $user = User::where('email', $credentials['email'])->first();

            if (! $user || ! Hash::check($credentials['password'], $user->password)) {
                return $this->sendError(
                    'Identifiants invalides.',
                    [],
                    401
                );
            }

            $plainTextToken = $user
                ->createToken("access_token of {$user->email}")
                ->plainTextToken;

            return $this->sendResponse(
                [
                    'user'         => UserResource::make($user),
                    'access_token' => $plainTextToken,
                ],
                'Connexion réussie.',
            );

        }catch (\Throwable $th) {
            return $this->sendError('Erreur interne lors de la connexion.', ['error' => $th->getMessage()], 500);
        }
    }

    public function register(RegisterRequest $request){
        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

     /**
     * Déconnexion : supprime le token courant.
     */

     public function logout(Request $request): JsonResponse
     {
         $user = $request->user();
         if (! $user) {
             return $this->sendError(
                 'Non authentifié.',
                 [],
                 401
             );
         }
     
         try {
             $user->currentAccessToken()->delete();
     
             return $this->sendResponse(
                 null,
                 'Déconnexion réussie !',
                 200
             );
         } catch (\Throwable $th) {
             return $this->sendError(
                 'Erreur lors de la déconnexion.',
                 ['errors' => $th->getMessage()],
                 500
             );
         }
     }
     
}
