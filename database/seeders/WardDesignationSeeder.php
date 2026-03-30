<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WardDesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designations = [
            [
                'en' => 'Ward Chairperson',
                'np' => 'वडा अध्यक्ष'
            ],
            [
                'en' => 'Ward Member',
                'np' => 'वडा सदस्य'
            ],
            [
                'en' => 'Ward Secretary',
                'np' => 'वडा सचिव'
            ],
            [
                'en' => 'Office Helper',
                'np' => 'कार्यालय सहयोगी'
            ],
            [
                'en' => 'Social Mobilizer',
                'np' => 'सामाजिक परिचालक'
            ],
            [
                'en' => 'Technical Assistant (Engineer/Sub-Engineer)',
                'np' => 'प्राविधिक सहायक (इन्जिनियर/सब-इन्जिनियर)'
            ]
        ];

        foreach ($designations as $designation) {
            $wd = \App\Models\WardDesignation::create();
            \App\Models\WardDesignationTranslation::create([
                'ward_designation_id' => $wd->id,
                'locale' => 'en',
                'name' => $designation['en']
            ]);
            \App\Models\WardDesignationTranslation::create([
                'ward_designation_id' => $wd->id,
                'locale' => 'np',
                'name' => $designation['np']
            ]);
        }
    }
}
