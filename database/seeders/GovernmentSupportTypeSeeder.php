<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GovernmentSupportType;

class GovernmentSupportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $governmentSupportTypes = [
            ['en' => 'Old Age Allowance', 'np' => 'बृद्ध भत्ता'],
            ['en' => 'Single Woman Allowance', 'np' => 'एकल महिला भत्ता'],
            ['en' => 'Disability Allowance', 'np' => 'अपाङ्गता भत्ता'],
            ['en' => 'Pension', 'np' => 'पेन्सन'],
            ['en' => 'Special Allowance and Benefits', 'np' => 'विशेष भत्ता तथा सुविधा'],
            ['en' => 'Other', 'np' => 'अन्य'],
        ];

        foreach ($governmentSupportTypes as $item) {
            $governmentSupportType = GovernmentSupportType::create();
            $governmentSupportType->translations()->createMany([
                ['locale' => 'en', 'name' => $item['en']],
                ['locale' => 'np', 'name' => $item['np']],
            ]);
        }
    }
}
