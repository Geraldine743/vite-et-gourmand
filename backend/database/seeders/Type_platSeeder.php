<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Type_plat;

class Type_platSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typesPlat = ['Entrée', 'Plat principal', 'Dessert'];

        foreach ($typesPlat as $type) {
            Type_plat::firstOrCreate(['libelle' => $type]);
        }
    }
}
