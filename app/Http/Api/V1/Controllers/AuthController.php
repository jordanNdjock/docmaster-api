<?php

namespace App\Http\Api\V1\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(LoginRequest $request): JsonResponse{
        try {
            $credentials = $request->only('email', 'password', 'nom_utilisateur');
            if(auth()->attempt($credentials)){
                return $this->sendResponse([
                    'user' => UserResource::make(auth('sanctum')->user()),
                    'token' => auth('sanctum')->user()->createToken('auth_token')->plainTextToken
                ], 'Connexion réussie !');
            }
            return $this->sendError('Identifiants invalides',[],401);
        } catch (\Throwable $th) {
            return $this->sendError('Erreur lors de la connexion', ['error' => $th->getMessage()], 500);
        }
    }

    public function register(RegisterRequest $request){
        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function logout(){
        try {
            auth('sanctum')->user()->tokens()->delete();
            return $this->sendResponse('Déconnexion réussie !');
        } catch (\Throwable $th) {
            return $this->sendError('Erreur lors de la déconnexion',['error' => $th->getMessage()], 500);
        }
    }
}
