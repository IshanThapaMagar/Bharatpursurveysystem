<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MotherTongueProficiency;

class MotherTongueProficiencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proficiencies = [
            ['en' => 'Some', 'np' => 'केही'],
            ['en' => 'Partial', 'np' => 'आँशिक'],
            ['en' => 'Good', 'np' => 'राम्ररी'],
        ];

        foreach ($proficiencies as $item) {
            $proficiency = MotherTongueProficiency::create();
            $proficiency->translations()->createMany([
                ['locale' => 'en', 'name' => $item['en']],
                ['locale' => 'np', 'name' => $item['np']],
            ]);
        }
    }
}