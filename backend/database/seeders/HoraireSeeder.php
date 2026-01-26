<?php

namespace Database\Seeders;

use App\Models\Horaire;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HoraireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $horaires = [
            ['jour' => 'Lundi', 'heure_ouverture' => '09:00', 'heure_fermeture' => '18:00'],
            ['jour' => 'Mardi', 'heure_ouverture' => '09:00', 'heure_fermeture' => '18:00'],
            ['jour' => 'Mercredi', 'heure_ouverture' => '09:00', 'heure_fermeture' => '18:00'],
            ['jour' => 'Jeudi', 'heure_ouverture' => '09:00', 'heure_fermeture' => '18:00'],
            ['jour' => 'Vendredi', 'heure_ouverture' => '09:00', 'heure_fermeture' => '20:00'],
            ['jour' => 'Samedi', 'heure_ouverture' => '10:00', 'heure_fermeture' => '20:00'],
            
        ];
        foreach ($horaires as $horaire) {
            Horaire::create($horaire);
        }
    }
}
