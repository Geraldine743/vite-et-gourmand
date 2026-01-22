<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->tokens()->delete(); 
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user->load('role'),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnexion réussie']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user()->load('role'));
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'lastname' => 'required|string|max:50',
            'firstname' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'lastname' => $validatedData['lastname'],
            'firstname' => $validatedData['firstname'],
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
            'city' => $validatedData['city'],
            'postal_code' => $validatedData['postal_code'],
            'country' => $validatedData['country'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role_id' => 3, 
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Compte créé avec succès',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user->load('role'),
        ], 201);
    }

}
