<?php

namespace App\Http\Controllers\Api;

use App\Models\Menu;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $validatedData = $request->validate([
            'date_prestation' => 'required|date|after:today',
            'date_livraison' => 'required|date|after:today',
            'adresse_livraison' => 'required|string|max:50',
            'ville_livraison' => 'required|string|max:50',
            'code_postal_livraison' => 'required|string|max:20',
            'heure_livraison' => 'required|string|max:10',
            'menus' => 'required|array|min:1',
            'menus.*.id' => 'required|integer|exists:menus,id',
            'menus.*.nb_personnes' => 'required|integer|min:1',
        ]);
        
        try {
            return DB::transaction(function () use ($validatedData, $request) {
                $totalCommande = 0;
                $menusToAttach = [];

                foreach ($validatedData['menus'] as $menuData) {
                    $menu = Menu::where('id', $menuData['id'])->lockForUpdate()->first();
                
                    $nbConvives = $menuData['nb_personnes'];
                    if ($nbConvives < $menu->nb_personne_min) {
                        throw new \Exception(400, "Le menu {$menu->titre} requiert minimum {$menu->nb_personne_min} personnes.");
                    }
                    if ($menu->stock < $nbConvives) {
                        throw new \Exception(400, "Stock insuffisant pour {$menu->titre}.");
                    }

                    $prixUnitaireFixe = $menu->prix_par_personne;
                    $prixTotalLigne = $prixUnitaireFixe * $nbConvives;
                    $totalCommande += $prixTotalLigne;

                    $menusToAttach[$menu->id] = [
                        'nb_personnes' => $nbConvives,
                        'prix_unitaire_fixe' => $prixUnitaireFixe,
                        'prix_total_ligne' => $prixTotalLigne,
                    ];

                    $menu->decrement('stock', $nbConvives);
                }

                $prixLivraison = 5.00;
                $totalCommande += $prixLivraison;

                $commande = Commande::create([
                    'user_id' => $request->user('sanctum')->id,
                    'date_prestation' => $validatedData['date_prestation'],
                    'date_livraison' => $validatedData['date_livraison'],
                    'adresse_livraison' => $validatedData['adresse_livraison'],
                    'ville_livraison' => $validatedData['ville_livraison'],
                    'code_postal_livraison' => $validatedData['code_postal_livraison'],
                    'heure_livraison' => $validatedData['heure_livraison'],
                    'prix_livraison' => $prixLivraison,
                    'total_commande' => $totalCommande,
                    'statut_commande_id' => 1,
                ]);
                $commande->menus()->attach($menusToAttach);
                return $commande->load(['statutCommande', 'menus']);
            });
        }catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la création de la commande: ' . $e->getMessage()], 500);
        };
    }
    /**
     * Display the specified resource.
     */
    public function show(Request $request, Commande $commande)
    {
        $currentUser = $request->user('sanctum');
        if ($currentUser->id !== $commande->user_id && !$currentUser->isStaff()) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }
        return response()->json($commande->load(['statutCommande', 'menus']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Commande $commande)
    {
        $currentUser = $request->user('sanctum');
        if ($currentUser->id !== $commande->user_id && !$currentUser->isStaff()) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        if ($currentUser->id === $commande->user_id){
            if ($commande->statut_commande_id !== 1) {
                return response()->json(['message' => 'Impossible de modifier la commande. Veuillez contacter Vite&Goumand'], 400);
            }

            $validatedData = $request->validate([
                'date_prestation' => 'sometimes|date|after:today',
                'date_livraison' => 'sometimes|date|after:today',
                'adresse_livraison' => 'sometimes|string|max:50',
                'ville_livraison' => 'sometimes|string|max:50',
                'code_postal_livraison' => 'sometimes|string|max:20',
                'heure_livraison' => 'sometimes|string|max:10',
            ]);

            $commande->update($validatedData);
            return response()->json(['message' => 'Commande mise à jour', 'commande' => $commande->load(['statutCommande', 'menus'])]);
        }

        if ($currentUser->isStaff()) {
            $validatedData = $request->validate([
                'statut_commande_id' => 'required|exists:statut_commandes,id',
                'pret_materiel' => 'boolean',
                'retour_materiel' => 'boolean'
            ]);

            $commande->update($validatedData);
            return response()->json(['message' => 'Commande mise à jour', 'commande' => $commande->load(['statutCommande', 'menus'])]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
