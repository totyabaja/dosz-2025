<?php

namespace Database\Seeders;

use App\Models\ScientificSubfield;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScientificSubfieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subfields = [
            // Agrártudományok
            ['name' => 'Állatorvosi tudományok', 'scientific_field_id' => 1],
            ['name' => 'Állattenyésztési tudományok', 'scientific_field_id' => 1],
            ['name' => 'Élelmiszertudományok', 'scientific_field_id' => 1],
            ['name' => 'Erdészeti és vadgazdálkodási tudományok', 'scientific_field_id' => 1],
            ['name' => 'Növénytermesztési és kertészeti tudományok', 'scientific_field_id' => 1],

            // Bölcsészettudományok
            ['name' => 'Történelemtudományok', 'scientific_field_id' => 2],
            ['name' => 'Pszichológiai tudományok', 'scientific_field_id' => 2],
            ['name' => 'Nyelvtudományok', 'scientific_field_id' => 2],
            ['name' => 'Neveléstudományok', 'scientific_field_id' => 2],
            ['name' => 'Néprajz- és kulturális antropológia', 'scientific_field_id' => 2],
            ['name' => 'Művészettudomány', 'scientific_field_id' => 2],
            ['name' => 'Művészettörténeti és művelődéstörténeti tudományok', 'scientific_field_id' => 2],
            ['name' => 'Irodalom- és kultúratudományok', 'scientific_field_id' => 2],
            ['name' => 'Filozófiai tudományok', 'scientific_field_id' => 2],
            ['name' => 'Vallástudományok', 'scientific_field_id' => 2],

            // Hittudomány
            ['name' => 'Hittudományok', 'scientific_field_id' => 3],

            // Műszaki tudományok
            ['name' => 'Agrárműszaki tudományok', 'scientific_field_id' => 4],
            ['name' => 'Villamosmérnöki tudományok', 'scientific_field_id' => 4],
            ['name' => 'Közlekedéstudományok', 'scientific_field_id' => 4],
            ['name' => 'Közlekedés- és járműtudományok', 'scientific_field_id' => 4],
            ['name' => 'Katonai műszaki tudományok', 'scientific_field_id' => 4],
            ['name' => 'Informatikai tudományok', 'scientific_field_id' => 4],
            ['name' => 'Gépészeti tudományok', 'scientific_field_id' => 4],
            ['name' => 'Építőmérnöki tudományok', 'scientific_field_id' => 4],
            ['name' => 'Építészmérnöki tudományok', 'scientific_field_id' => 4],
            ['name' => 'Bio-, környezet- és vegyészmérnöki tudományok', 'scientific_field_id' => 4],

            // Művészetek
            ['name' => 'Zeneművészet', 'scientific_field_id' => 5],
            ['name' => 'Tánc- és mozdulatművészet', 'scientific_field_id' => 5],
            ['name' => 'Színházművészet', 'scientific_field_id' => 5],
            ['name' => 'Multimédia-művészet', 'scientific_field_id' => 5],
            ['name' => 'Képzőművészet', 'scientific_field_id' => 5],
            ['name' => 'Iparművészet', 'scientific_field_id' => 5],
            ['name' => 'Film- és videoművészet', 'scientific_field_id' => 5],
            ['name' => 'Építőművészet', 'scientific_field_id' => 5],

            // Orvos- és egészségtudományok
            ['name' => 'Sporttudományok', 'scientific_field_id' => 6],
            ['name' => 'Klinikai orvostudományok', 'scientific_field_id' => 6],
            ['name' => 'Gyógyszerészeti tudományok', 'scientific_field_id' => 6],
            ['name' => 'Elméleti orvostudományok', 'scientific_field_id' => 6],
            ['name' => 'Egészségtudományok', 'scientific_field_id' => 6],

            // Társadalomtudományok
            ['name' => 'Szociológiai tudományok', 'scientific_field_id' => 7],
            ['name' => 'Rendészettudomány', 'scientific_field_id' => 7],
            ['name' => 'Regionális tudományok', 'scientific_field_id' => 7],
            ['name' => 'Politikatudományok', 'scientific_field_id' => 7],
            ['name' => 'Média- és kommunikációs tudományok', 'scientific_field_id' => 7],
            ['name' => 'Közigazgatás-tudományok', 'scientific_field_id' => 7],
            ['name' => 'Közgazdaságtudományok', 'scientific_field_id' => 7],
            ['name' => 'Hadtudományok', 'scientific_field_id' => 7],
            ['name' => 'Gazdálkodás- és szervezéstudományok', 'scientific_field_id' => 7],
            ['name' => 'Állam- és jogtudományok', 'scientific_field_id' => 7],

            // Természettudományok
            ['name' => 'Környezettudományok', 'scientific_field_id' => 8],
            ['name' => 'Kémiai tudományok', 'scientific_field_id' => 8],
            ['name' => 'Földtudományok', 'scientific_field_id' => 8],
            ['name' => 'Fizikai tudományok', 'scientific_field_id' => 8],
            ['name' => 'Biológiai tudományok', 'scientific_field_id' => 8],
            ['name' => 'Matematika- és számítástudományok', 'scientific_field_id' => 8],
        ];

        foreach ($subfields as $subfield) {
            ScientificSubfield::create($subfield);
        }
    }
}
