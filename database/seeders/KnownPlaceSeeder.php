<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KnownPlace;

class KnownPlaceSeeder extends Seeder
{
    public function run(): void
    {
        $places = [
            ['name' => 'Zuidpark', 'lat' => 51.0345, 'lon' => 3.7167],
            ['name' => 'Citadelpark', 'lat' => 51.0423, 'lon' => 3.7271],
            ['name' => 'Dok Noord', 'lat' => 51.0651, 'lon' => 3.7365],
            ['name' => 'Sint-Pietersstation', 'lat' => 51.0370, 'lon' => 3.7096],
            ['name' => 'Vrijdagmarkt', 'lat' => 51.0578, 'lon' => 3.7207],
            ['name' => 'St. Rumbold\'s Cathedral', 'lat' => 51.0256, 'lon' => 4.4789],
            ['name' => 'Mechelen Railway Station', 'lat' => 51.0219, 'lon' => 4.4778],
            ['name' => 'Planckendael Zoo', 'lat' => 51.0361, 'lon' => 4.5644],
            ['name' => 'Grand Place Mechelen', 'lat' => 51.0269, 'lon' => 4.4771],
            ['name' => 'Kazerne Dossin Museum', 'lat' => 51.0282, 'lon' => 4.4772],
            ['name' => 'Leuven Town Hall', 'lat' => 50.8798, 'lon' => 4.7008],
            ['name' => 'University Library Leuven', 'lat' => 50.8793, 'lon' => 4.7000],
            ['name' => 'Grand Market Square Leuven', 'lat' => 50.8803, 'lon' => 4.7014],
            ['name' => 'M-Museum Leuven', 'lat' => 50.8771, 'lon' => 4.7019],
            ['name' => 'St. Peter\'s Church Leuven', 'lat' => 50.8802, 'lon' => 4.6998],
        ];

        foreach ($places as $place) {
            KnownPlace::create($place);
        }
       
    }
}
