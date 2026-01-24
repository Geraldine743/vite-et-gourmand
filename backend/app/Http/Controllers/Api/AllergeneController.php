<?php

namespace App\Http\Controllers\Api;

use App\Models\Allergene;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AllergeneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Allergene::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'libelle' => 'required|string|max:50|unique:allergenes,libelle',
        ]);

        $allergene = Allergene::create($validatedData);
        return response()->json($allergene, 201);
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
    public function update(Request $request, Allergene $allergene)
    {
        $validatedData = $request->validate([
            'libelle' => 'required|string|max:50|unique:allergenes,libelle,' . $allergene->id,
        ]);
        $allergene->update($validatedData);
        return response()->json($allergene, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Allergene $allergene)
    {
        $allergene->delete();
        return response()->json(['message' => 'Allergène supprimé avec succès'], 200);
    }
}
