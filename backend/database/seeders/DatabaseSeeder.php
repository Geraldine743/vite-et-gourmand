<?php

namespace Database\Seeders;

use App\Models\Avis;
use App\Models\Commande;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $this->call(AllergeneSeeder::class);
        $this->call(TypePlatSeeder::class);
        $this->call(PlatSeeder::class);
        $this->call(RegimeSeeder::class);
        $this->call(ThemeSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(HoraireSeeder::class);
        $this->call(StatutCommandeSeeder::class);

        User::factory()->create([
            'firstname' => 'Dupond',    
            'lastname' => 'José',
            'email' => 'dupond.jose@example.com',
            "password" => Hash::make("changeMe123!"),
            'phone' => '0601020304',
            'address' => '1 rue du Code',
            'city' => 'Toulouse',
            'postal_code' => '71000',
            'country' => 'France',
            'role_id' => 1,
        ]);

        User::factory()->create([
            'firstname' => 'Dupont',    
            'lastname' => 'Josette',
            'email' => 'dupont.josette@example.com',
            "password" => Hash::make("password123!"),
            'phone' => '0689070605',
            'address' => '20 rue du Bug',
            'city' => 'Toulouse',
            'postal_code' => '71000',
            'country' => 'France',
            'role_id' => 2,
        ]);

        User::factory()->create([
            'firstname' => 'Milou',    
            'lastname' => 'Tintin',
            'email' => 'milou.tintin@example.com',
            "password" => Hash::make("snowy123!"),
            'phone' => '0678050403',
            'address' => '15 rue du Mystere',
            'city' => 'Toulouse',
            'postal_code' => '71000',
            'country' => 'France',
            'role_id' => 3,
        ]);

        $user=User::factory(10)->create();
        Avis::factory(30)->recycle($user)->create();
        Commande::factory(50)->recycle($user)->create();
    }
}
