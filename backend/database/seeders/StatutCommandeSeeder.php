<?php

namespace Database\Seeders;

use App\Models\StatutCommande;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatutCommandeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuts = [
            ['libelle' => 'acceptée'],
            ['libelle' => 'en préparation'],
            ['libelle' => 'En cours de livraison'],
            ['libelle' => 'Livrée'],
            ['libelle' => 'en attente du retour de matériel'],
            ['libelle' => 'terminée'],
            ['libelle' => 'Annulée'],
        ];

        foreach ($statuts as $statut) {
            StatutCommande::create($statut);
        }
    }
}
