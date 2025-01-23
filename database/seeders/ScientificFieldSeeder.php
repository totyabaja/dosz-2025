<?php

namespace Database\Seeders;

use App\Models\ScientificField;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScientificFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [
            'Agrártudományok',
            'Bölcsészettudományok',
            'Hittudomány',
            'Műszaki tudományok',
            'Művészetek',
            'Orvos- és egészségtudományok',
            'Társadalomtudományok',
            'Természettudományok',
        ];

        foreach ($fields as $field) {
            ScientificField::create(['name' => $field]);
        }
    }
}
