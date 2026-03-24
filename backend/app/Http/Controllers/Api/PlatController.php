<?php

namespace App\Http\Controllers\Api;

use App\Models\Plat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Plat::with('allergenes', 'typePlat')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'titre_plat' => 'required|string|max:50',
            'image' => 'nullable|image|max:2048',
            'type_plat_id' => 'required|exists:type_plats,id',
            'allergenes' => 'array',
            'allergenes.*' => 'exists:allergenes,id',
        ]);
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('plats', 'public');
        }

        $plat = Plat::create([
            'titre_plat' => $validatedData['titre_plat'],
            'image' => $imagePath,
            'type_plat_id' => $validatedData['type_plat_id'],
        ]);

        if ($request->has('allergenes')) {
            $plat->allergenes()->attach($request->allergenes);
        }

        return response()->json($plat, 201);
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
    public function update(Request $request, Plat $plat)
    {
        $validatedData = $request->validate([
            'titre_plat' => 'sometimes|required|string|max:50',
            'image' => 'sometimes|nullable|image|max:2048',
            'type_plat_id' => 'sometimes|required|exists:type_plats,id',
            'allergenes' => 'sometimes|array',
            'allergenes.*' => 'exists:allergenes,id',
        ]);
        if ($request->hasFile('image')) {
            if ($plat->image) {
                Storage::disk('public')->delete($plat->image);
            }
            $validatedData['image'] = $request->file('image')->store('plats', 'public');
        }else{
            unset($validatedData['image']);
        }
        $plat->update($validatedData);
        if ($request->has('allergenes')) {
            $plat->allergenes()->sync($request->allergenes);
        }
        return response()->json($plat);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plat $plat)
    {
        if ($plat->image) {
            Storage::disk('public')->delete($plat->image);
        }
        $plat->delete();
        return response()->json(['message' => 'Plat supprimé avec succès.']);
    }
}
