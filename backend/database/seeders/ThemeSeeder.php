<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $themes = [
            ['libelle' => 'Classique'],
            ['libelle' => 'Gourmet'],
            ['libelle' => 'Asiatique'],
            ['libelle' => 'Italien'],
            ['libelle' => 'Noël'],
            ['libelle' => 'Anniversaire'],
            ['libelle' => 'Pâques'],
        ];
        foreach ($themes as $themeData) {
            Theme::firstOrCreate($themeData);
        }
    }
}
