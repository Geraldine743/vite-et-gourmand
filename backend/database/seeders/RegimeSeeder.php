<?php

namespace Database\Seeders;

use App\Models\Regime;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RegimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regimes = [
            ['libelle' => 'Végétarien'],
            ['libelle' => 'Végan'],
            ['libelle' => 'Sans gluten'],
            ['libelle' => 'Cétogène'],
            ['libelle' => 'Paléo'],
        ];
        foreach ($regimes as $regimeData) {
            Regime::firstOrCreate($regimeData);
        }
    }
}
