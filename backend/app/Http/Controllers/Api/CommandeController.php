<?php

namespace App\Http\Controllers\Api;

use App\Models\Commande;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentUser = request()->user('sanctum');
        if (!$currentUser) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        if ($currentUser && $currentUser->isStaff()) {
            return Commande::with(['user','statutCommande', 'menus'])->get();
        }
        return Commande::with(['statutCommande', 'menus'])->where('user_id', $currentUser->id)->get();
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
