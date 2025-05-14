<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Flare;

class FlareSeeder extends Seeder
{
    
    
    public function run(): void
    {
        Flare::create([
            'user_id' => 1,
            'latitude' => 37.7749,
            'longitude' => -122.4194,
            'note' => 'Great spot for coffee!',
            'category' => 'regular',
        ]);
    }
}
