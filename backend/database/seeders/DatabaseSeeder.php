<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        // User::factory(10)->create();

        User::factory()->create([
            'firstname' => 'Test',    
            'lastname' => 'User',
            'email' => 'test@example.com',
            "password" => "password",
            'phone' => '0601020304',
            'address' => '1 rue du Code',
            'city' => 'Paris',
            'postal_code' => '75000',
            'country' => 'France',
            'role_id' => 1,
        ]);
    }
}
