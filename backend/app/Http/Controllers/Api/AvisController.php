<?php

namespace App\Http\Controllers\Api;

use App\Models\Avis;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AvisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentUser = $request->user('sanctum');
        if ($currentUser && $currentUser->isStaff()) {
            return Avis::with('user')->get();
        }
        return Avis::where('publie', true)->with('user')->get();
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'description' => 'required|string|max:1000',
            'note' => 'required|integer|min:1|max:5',
        ]);
        $validatedData['user_id'] = $request->user('sanctum')->id;
        $validatedData['publie'] = false; 
        $avis = Avis::create($validatedData);
        return response()->json([
            'message' => 'Avis enregistré avec succès. Il sera publié après validation.', 
            'avis' => $avis],
            201);
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
    public function update(Request $request, Avis $avis)
    {
        $validatedData = $request->validate([
            'description' => 'sometimes|required|string|max:1000',
            'note' => 'sometimes|required|integer|min:1|max:5',
            'publie' => 'sometimes|required|boolean',
        ]);
        $avis->update($validatedData);
        return response()->json(['message' => 'Avis mis à jour avec succès', 
        'avis' => $avis], 
        200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Avis $avis)
    {
        $avis->delete();
        return response()->json(['message' => 'Avis supprimé avec succès'], 200);
    }
}
