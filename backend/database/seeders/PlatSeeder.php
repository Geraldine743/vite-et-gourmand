<?php

namespace Database\Seeders;

use App\Models\Allergene;
use App\Models\Plat;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allergenes = Allergene::all();
        Plat::factory()
            ->count(50)
            ->create()
            ->each(function (Plat $plat) use ($allergenes) {
                if ($allergenes->isEmpty()) {
                    return;
                }
                $plat->allergenes()->attach(
                    $allergenes->random(rand(0, 3))->pluck('id')->toArray()
                );
            });
    }
}
