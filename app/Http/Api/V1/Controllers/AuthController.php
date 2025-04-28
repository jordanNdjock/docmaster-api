<?php

namespace App\Http\Api\V1\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(LoginRequest $request){
        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
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
            return $this->sendResponse('Logout successfully');
        } catch (\Throwable $th) {
            return $this->sendError('logout successfully',['error' => $th->getMessage()], 500);
        }
    }
}
