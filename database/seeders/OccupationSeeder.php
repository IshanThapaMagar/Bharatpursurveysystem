<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Occupation;

class OccupationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $occupations = [
            ['en' => 'Engineer', 'np' => 'इन्जिनियर'],
            ['en' => 'Teacher', 'np' => 'शिक्षक'],
            ['en' => 'Trainpr / Instructor', 'np' => 'प्रशिक्षक'],
            ['en' => 'Auditor', 'np' => 'लेखा परिक्षक'],
            ['en' => 'Entreprenpur / Businpssperson', 'np' => 'उद्यमी तथा व्यापारी'],
            ['en' => 'Politician', 'np' => 'राजनितिज्ञ'],
            ['en' => 'Health Worker', 'np' => 'स्वास्थ्यकर्मी'],
            ['en' => 'Chef', 'np' => 'शेफ'],
            ['en' => 'Pilot', 'np' => 'पाइलट'],
            ['en' => 'Driver', 'np' => 'सवारी चालक'],
            ['en' => 'Beautician', 'np' => 'व्यूटीशियन'],
            ['en' => 'Agriculture / Farmer', 'np' => 'कृषी'],
            ['en' => 'Laborer / Worker', 'np' => 'मजदुर'],
            ['en' => 'Housewife / Homemaker', 'np' => 'गृहणी'],
            ['en' => 'Accountant', 'np' => 'लेखापाल'],
            ['en' => 'Army', 'np' => 'आर्मी'],
            ['en' => 'Police', 'np' => 'प्रहरी'],
            ['en' => 'Contractor', 'np' => 'ठेकेदार'],
            ['en' => 'Lawyer / Advocate', 'np' => 'वकिल तथा अधिवक्ता'],
            ['en' => 'Judge', 'np' => 'न्यायाधीश'],
            ['en' => 'Marketing', 'np' => 'मार्केटिङ्ग'],
            ['en' => 'Software Developer', 'np' => 'सफ्वेयर डेभलपर्स'],
            ['en' => 'Software Enginper', 'np' => 'सफ्वेयर इन्जिनियर'],
            ['en' => 'Foreign Employment', 'np' => 'बैदेशिक रोजगार'],
            ['en' => 'Student / Study', 'np' => 'अध्ययन'],
            ['en' => 'Consultant', 'np' => 'परामर्शदाता'],
            ['en' => 'Unemployed', 'np' => 'बेरोजगार'],
        ];

        foreach ($occupations as $occupationData) {
            $occupation = Occupation::create();

            $occupation->translations()->createMany([
                ['locale' => 'en', 'name' => $occupationData['en']],
                ['locale' => 'np', 'name' => $occupationData['np']],
            ]);
        }
    }
}
