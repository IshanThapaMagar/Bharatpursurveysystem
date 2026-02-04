<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EducationLevel;

class EducationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $educationLevels = [
            ['en' => 'PhD', 'np' => 'पिएचडि'],
            ['en' => 'M.Phil', 'np' => 'एमफिल'],
            ['en' => 'Masters', 'np' => 'मास्टर डिग्री'],
            ['en' => 'Bachelors', 'np' => 'स्नातक'],
            ['en' => 'Secondary', 'np' => 'माध्यामिक'],
            ['en' => 'Lower Secondary', 'np' => 'आधारभुत'],
            ['en' => 'Primary', 'np' => 'पूर्व आधारभुत'],
            ['en' => 'Literate', 'np' => 'सामान्य शिक्षा'],
            ['en' => 'Illiterate', 'np' => 'निरक्षर'],
            ['en' => 'Not Specified', 'np' => 'शैक्षिक योग्यता'],
        ];

        foreach ($educationLevels as $item) {
            $educationLevel = EducationLevel::create();
            $educationLevel->translations()->createMany([
                ['locale' => 'en', 'name' => $item['en']],
                ['locale' => 'np', 'name' => $item['np']],
            ]);
        }
    }
}