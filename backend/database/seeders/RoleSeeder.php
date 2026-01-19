<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['libelle' => 'Admin'],
            ['libelle' => 'Employee'],
            ['libelle' => 'User'],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate($roleData);
        }
    }
}
