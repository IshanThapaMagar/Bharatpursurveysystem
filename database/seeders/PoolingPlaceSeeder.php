<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PoolingPlace;

class PoolingPlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $poolingPlaces = [
            ['en' => 'Related Ward', 'np' => 'सम्बन्धित वडा'],
            ['en' => 'Different Ward', 'np' => 'फरक वडा'],
        ];

        foreach ($poolingPlaces as $item) {
            $poolingPlace = PoolingPlace::create();
            $poolingPlace->translations()->createMany([
                ['locale' => 'en', 'name' => $item['en']],
                ['locale' => 'np', 'name' => $item['np']],
            ]);
        }
    }
}
