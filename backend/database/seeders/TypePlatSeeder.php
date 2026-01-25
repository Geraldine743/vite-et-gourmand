<?php

namespace Database\Seeders;

use App\Models\TypePlat;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class TypePlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typesPlat = ['Entrée', 'Plat principal', 'Dessert'];

        foreach ($typesPlat as $type) {
            TypePlat::firstOrCreate(['libelle' => $type]);
        }
    }
}
