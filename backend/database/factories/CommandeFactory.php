<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\User;
use App\Models\Commande;
use Illuminate\Support\Str;
use App\Models\StatutCommande;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commande>
 */
class CommandeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $datePrestation = $this->faker->dateTimeBetween('+5 day', '+2 month');
        return [
            'user_id' => User::factory(),
            'numero_commande' => 'CMD-' . strtoupper(Str::random(8)),
            'statut_commande_id' => StatutCommande::inRandomOrder()->first()->id,
            'date_commande' => now(),
            'date_prestation' => $datePrestation,
            'date_livraison' => $datePrestation, 
            'heure_livraison' => '12:00',
            'adresse_livraison' => $this->faker->address(),
            'ville_livraison' => $this->faker->city(),
            'code_postal_livraison' => $this->faker->postcode(),
            'prix_livraison' => 5.00,
            'total_commande' => 0,
            'pret_materiel' => $this->faker->boolean(20), 
            'retour_materiel' => false
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Commande $commande) {
            $menus = Menu::inRandomOrder()->take(rand(1, 3))->get();
            $totalMenu = 0;
            $pivotData = [];

            foreach ($menus as $menu) {
                $qty = rand($menu->nb_personne_min, $menu->nb_personne_min + 20);
                $prixUnitaire = $menu->prix_par_personne;
                $prixLigne = $prixUnitaire * $qty;

                $pivotData[$menu->id] = [
                    'nb_personnes' => $qty,
                    'prix_unitaire_fixe' => $prixUnitaire,
                    'prix_total_ligne' => $prixLigne
                ];

                $totalMenu += $prixLigne;
            }

            $commande->menus()->attach($pivotData);
            $totalFinal = $totalMenu + $commande->prix_livraison;
            $commande->update(['total_commande' => $totalFinal]);
        });
    }
}
