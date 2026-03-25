<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Menu::with(['regime', 'theme', 'plats.allergenes'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'nb_personne_min' => 'required|integer|min:1',
            'prix_par_personne' => 'required|numeric|min:0',
            'condition' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'regime_id' => 'required|exists:regimes,id',
            'theme_id' => 'required|exists:themes,id',
            'plats' => 'nullable|array',
            'plats.*' => 'exists:plats,id',
        ]);
        $menu = Menu::create($validated);
        if ($request->has('plats')) {
            $menu->plats()->attach($request->plats);
        }
        return response()->json($menu, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {   
        $menu->load(['regime', 'theme', 'plats']);
        return response()->json($menu);
    }   
    
    /**
      * Update the specified resource in storage.
      */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'titre' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'nb_personne_min' => 'sometimes|required|integer|min:1',
            'prix_par_personne' => 'sometimes|required|numeric|min:0',
            'condition' => 'sometimes|nullable|string',
            'stock' => 'sometimes|required|integer|min:0',
            'regime_id' => 'sometimes|required|exists:regimes,id',
            'theme_id' => 'sometimes|required|exists:themes,id',
            'plats' => 'sometimes|nullable|array',
            'plats.*' => 'exists:plats,id',
        ]);
        $menu->update($validated);
        if ($request->has('plats')) {
            $menu->plats()->sync($request->plats);
        }
        return response()->json($menu);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response()->json(['message' => 'Menu a été supprimé avec succès.'], 200);
    }
}
