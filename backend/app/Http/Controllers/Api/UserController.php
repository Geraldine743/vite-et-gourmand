<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::with('role')->get();
    }

    public function getStaff()
    {
        $staff = User::with('role')->where('role_id', '=', 2)->get();
        
        return response()->json($staff);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
            'role_id' => 'required|exists:roles,id',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);
    
        $user = User::create($validatedData);
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user)
    {
        $currentUser = $request->user();
        if ($currentUser->id !== $user->id && !$currentUser->isStaff()) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }
        return $user->load('role');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $currentUser = $request->user();
        if ($currentUser->id !== $user->id && !$currentUser->isAdmin()) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $validatedData = $request->validate([
            'lastname' => 'sometimes|required|string|max:50',
            'firstname' => 'sometimes|required|string|max:50',
            'phone' => 'sometimes|required|string|max:20',
            'address' => 'sometimes|required|string|max:50',
            'city' => 'sometimes|required|string|max:50',
            'postal_code' => 'sometimes|required|string|max:20',
            'country' => 'sometimes|required|string|max:50',
            'email' => 'sometimes|required|string|email|max:50|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8|confirmed',
        ]);

        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $user->update($validatedData);
        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id) 
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur introuvable.'], 404);
        }

        $currentUser = $request->user();
        if ($currentUser && $currentUser->id === $user->id) {
            return response()->json(['message' => 'Vous ne pouvez pas supprimer votre propre compte.'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès !'], 200);
    }
}
