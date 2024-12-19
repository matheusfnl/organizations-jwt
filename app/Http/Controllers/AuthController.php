<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\{LoginRequest, RegisterRequest};
use App\Models\User;
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request) {
        $jwt = $this->generateToken($request);

        return response()->json([
            'token' => $jwt,
            'user' => auth()->user(),
        ]);
    }

    public function register(RegisterRequest $request) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $jwt = $this->generateToken($request);

        return response()->json([
            'token' => $jwt,
            'user' => $user,
        ], 201);
    }

    public function logout(Request $request) {
        auth()->logout();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    private function generateToken(Request $request) {
        if (! auth()->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            abort(Response::HTTP_UNAUTHORIZED, 'Unauthorized');
        }

        $user = auth()->user();
        $organization = $user->organizations()->first();
        $token = auth()->claims(['organization_id' => $organization->id])->fromUser($user);

        return $token;
    }
}
