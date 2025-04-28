<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Réponse succès simple.
     */

    protected function sendResponse(
        $data = null,
        string $message = '',
        int $status = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'status'  => $status,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    /**
     * Réponse erreur simple.
     */

    protected function sendError(
        string $message,
        array $errors = [],
        int $status = 404
    ): JsonResponse {
        $payload = [
            'success' => false,
            'status'  => $status,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }
}
