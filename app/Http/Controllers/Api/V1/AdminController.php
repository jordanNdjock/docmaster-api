<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Admin\Auth\LoginResquest;
use App\Services\AdminServices;
use App\Traits\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController
{
    use ApiResponse;
    public function __construct(
        protected AdminServices $adminServices,
    ) {}

    public function login(LoginResquest $request): JsonResponse
    {
        $credentials = $request->only('email', 'mdp');
        try {
            ['admin' => $admin, 'token' => $plainTextToken] =
            $this->adminServices->authenticate(
                $credentials['email'],
                $credentials['mdp']
            );

            return $this->sendResponse(
                [
                    'admin' => $admin,
                    'access_token' => $plainTextToken,
                ],
                'Connexion admin réussie.'
            );
        } catch (AuthenticationException $e) {
            return $this->sendError(
                $e->getMessage(),
                [],
                401
            );
        }
        catch (\Throwable $th) {
            return $this->sendError(
                'Erreur interne lors de la connexion.',
                ['error' => $th->getMessage()],
                500
            );
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $this->adminServices->logout($request->user('admin'));
            return $this->sendResponse([], 'Déconnexion admin réussie.');
        } catch (AuthenticationException $e) {
            return $this->sendError(
                $e->getMessage(),
                [],
                401
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                'Erreur interne lors de la déconnexion.',
                ['error' => $th->getMessage()],
                500
            );
        }
    }
}
