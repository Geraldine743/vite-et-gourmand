<?php

namespace Database\Seeders;

use App\Models\Allergene;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AllergeneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allergenes = [
            'Gluten',
            'Crustacés',
            'Œufs',
            'Poissons',
            'Arachides',
            'Soja',
            'Lait',
            'Fruits à coque',
            'Céleri',
            'Moutarde',
            'Graines de sésame',
            'Anhydride sulfureux et sulfites',
            'Lupin',
            'Mollusques'
        ];
        foreach ($allergenes as $libelle) {
            Allergene::firstOrCreate(['libelle' => $libelle]);
        }
    }
}
