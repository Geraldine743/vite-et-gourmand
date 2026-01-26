<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Horaire;
use Illuminate\Http\Request;

class HoraireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Horaire::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $valides = $request->validate([
            "jour" => "required|string",
            "heure_ouverture" => "required|string",
            "heure_fermeture" => "required|string",
        ]);
        Horaire::create($valides);
        return response()->json(["message" => "Horaire ajouté avec succès"], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Horaire $horaire)
    {
        return $horaire;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Horaire $horaire)
    {
        $valides = $request->validate([
            "jour" => "sometimes|string",
            "heure_ouverture" => "sometimes|string",
            "heure_fermeture" => "sometimes|string",
        ]);
        $horaire->update($valides);
        return response()->json(["message" => "Horaire mis à jour avec succès"], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Horaire $horaire)
    {
        $horaire->delete();
        return response()->json(["message" => "Horaire supprimé avec succès"], 200);
    }
}
