<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChiffreAffaire;
use Carbon\Carbon;

class ChiffreAffaireController extends Controller
{
    public function index()
    {
        $donnees = ChiffreAffaire::orderBy('date', 'asc')->take(30)->get();
        
        return response()->json($donnees);
    }

    public function generate()
    {
        ChiffreAffaire::truncate();

        for ($i = 30; $i >= 0; $i--) {
            ChiffreAffaire::create([
                'date' => Carbon::now()->subDays($i)->format('Y-m-d'),
                'montant_total' => rand(800, 2500) + (rand(0, 99) / 100), // ex: 1450.50
                'nombre_commandes' => rand(15, 60)
            ]);
        }

        return response()->json(['message' => 'Fausses données générées avec succès dans MongoDB !']);
    }
}
