<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Regime;
use Illuminate\Http\Request;

class RegimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Regime::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'libelle' => 'required|string|max:50|unique:regimes,libelle',
        ]);

        $regime = Regime::create($validatedData);
        return response()->json($regime, 201);
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
    public function update(Request $request, Regime $regime)
    {
        $validatedData = $request->validate([
            'libelle' => 'required|string|max:50|unique:regimes,libelle,' . $regime->id,
        ]);
        $regime->update($validatedData);
        return response()->json($regime, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Regime $regime)
    {
        $regime->delete();
        return response()->json(['message' => 'Régime supprimé avec succès'], 200);
    }
}
