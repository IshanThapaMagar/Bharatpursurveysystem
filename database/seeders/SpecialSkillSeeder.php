<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SpecialSkill;

class SpecialSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialSkills = [
            ['en' => 'Special Disability', 'np' => 'विशेष दश्रता'],
            ['en' => 'Engineer', 'np' => 'इन्जिनियर'],
            ['en' => 'Teacher', 'np' => 'शिक्षक'],
            ['en' => 'Trainer', 'np' => 'प्रशिक्षक'],
            ['en' => 'Auditor', 'np' => 'लेखा परिक्षक'],
            ['en' => 'Entrepreneur', 'np' => 'उद्यमी'],
            ['en' => 'Politician', 'np' => 'राजनितिज्ञ'],
            ['en' => 'Health Worker', 'np' => 'स्वास्थ्यकर्मी'],
            ['en' => 'Chef', 'np' => 'शेफ'],
            ['en' => 'Pilot', 'np' => 'पाइलट'],
            ['en' => 'Driver', 'np' => 'सवारी चालक'],
            ['en' => 'Other', 'np' => 'अन्य'],
        ];

        foreach ($specialSkills as $item) {
            $specialSkill = SpecialSkill::create();
            $specialSkill->translations()->createMany([
                ['locale' => 'en', 'name' => $item['en']],
                ['locale' => 'np', 'name' => $item['np']],
            ]);
        }
    }
}