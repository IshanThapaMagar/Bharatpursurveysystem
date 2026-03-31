<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MotherTongue;
use App\Models\Caste;

class LookupTablesSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Mother Tongues
        $motherTongues = [
            'नेपाली',
            'मैथिली',
            'भोजपुरी',
            'थारु',
            'तामाङ',
            'नेवार',
            'मगर',
            'बज्जिका',
            'उर्दु',
            'अवधी',
            'लिम्बु',
            'गुरुङ',
            'राई',
            'शेर्पा',
            'अन्य',
        ];

        foreach ($motherTongues as $tongue) {
            MotherTongue::firstOrCreate(['name' => $tongue]);
        }

        // Seed Castes
        $castes = [
            'ब्राह्मण',
            'क्षेत्री',
            'नेवार',
            'तामाङ',
            'मगर',
            'थारु',
            'गुरुङ',
            'राई',
            'लिम्बु',
            'शेर्पा',
            'यादव',
            'मुसलमान',
            'कामी',
            'दमाई',
            'सार्की',
            'दलित',
            'अन्य',
        ];

        foreach ($castes as $caste) {
            Caste::firstOrCreate(['name' => $caste]);
        }

        $this->command->info('Mother Tongues and Castes seeded successfully!');
    }
}