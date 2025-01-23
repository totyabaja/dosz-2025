<?php

namespace Database\Seeders;

use App\Models\ScientificState;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScientificStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $states = [
            'doktorandusz',
            'Doktorjelölt (régi képzés)',
            'Doktorvárományos',
            'Fokozatot szerzett',
            'Támogató',
        ];

        foreach ($states as $state) {
            ScientificState::create(['name' => $state]);
        }

    }
}
