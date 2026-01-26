<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Plat;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plats = Plat::all();

        Menu::factory(20)->create()->each(function ($menu) use ($plats) {
            if ($plats->isEmpty()) {
                return;
            }
            $menu->plats()->attach($plats->random(1,50)->pluck('id')->toArray());
        });
    }
}
