<?php

namespace App\Http\Controllers\Api;

use App\Models\Theme;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ThemeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Theme::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'libelle' => 'required|string|max:50|unique:themes,libelle',
        ]);
        $theme = Theme::create($validatedData);
        return response()->json($theme, 201);
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
    public function update(Request $request, Theme $theme)
    {
        $validatedData = $request->validate([
            'libelle' => 'required|string|max:50|unique:themes,libelle,' . $theme->id,
        ]);
        $theme->update($validatedData);
        return response()->json($theme, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Theme $theme)
    {
        $theme->delete();
        return response()->json(['message' => 'Thème supprimé avec succès'], 200);
    }
}
