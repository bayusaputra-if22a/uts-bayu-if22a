<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }
        $credential = $validator->validated();
        if (Auth::attempt($credential)) {
            $user = Auth::user();
            $payload = [
                'iss' => 'Laravel Bayu',
                'role' => $user->role,
                'id' => $user->id,
                'name' => $user->name,
                'iat' => now()->timestamp,
                'nbf' => now()->timestamp + 3600,
            ];
            $jwt = JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');
            return response()->json([
                'Bearer ' => $jwt
            ], 200);
            return response()->json([
                'success' => true,
                'message' => 'Login success',
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Login failed',
        ], 401);
    }
}
