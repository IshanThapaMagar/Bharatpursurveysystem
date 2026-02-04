<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InstitutionType;

class InstitutionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institutionTypes = [
            ['en' => 'Educational Institute', 'np' => 'कलेज/स्कूल'],
            ['en' => 'Community/Government', 'np' => 'सरकारी तथा सामूदायिक'],
            ['en' => 'Institutional', 'np' => 'निजी तथा संस्थागत'],
            ['en' => 'International', 'np' => 'बैदेशिक अध्ययन'],
        ];

        foreach ($institutionTypes as $item) {
            $institutionType = InstitutionType::create();
            $institutionType->translations()->createMany([
                ['locale' => 'en', 'name' => $item['en']],
                ['locale' => 'np', 'name' => $item['np']],
            ]);
        }
    }
}