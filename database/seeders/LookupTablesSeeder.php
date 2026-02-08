<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MotherTongue;
use App\Models\Caste;
use App\Models\Tole;
use App\Models\Ward;

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

        // Seed Toles for each Ward
        // Get all wards from the database
        $wards = Ward::all();

        // Common tole names in Nepal (you can customize per ward)
        $commonToles = [
            'बौद्ध टोल',
            'ब्राह्मण टोल',
            'चोक',
            'दलित टोल',
            'मगर गाउँ',
            'नयाँ बस्ती',
            'पुरानो बस्ती',
            'मध्य टोल',
            'उत्तर टोल',
            'दक्षिण टोल',
            'पूर्व टोल',
            'पश्चिम टोल',
        ];

        foreach ($wards as $ward) {
            // You can customize toles per ward or use common ones
            // For example, Ward 1 might have different toles than Ward 2
            
            // Option 1: Same toles for all wards
            foreach ($commonToles as $toleName) {
                Tole::firstOrCreate([
                    'name' => $toleName,
                    'ward_id' => $ward->id,
                ]);
            }

            // Option 2: Specific toles per ward (uncomment and customize as needed)
            /*
            if ($ward->ward_no == 1) {
                $wardSpecificToles = [
                    'काठमाडौं चोक',
                    'रानीपोखरी टोल',
                    'जमल टोल',
                ];
                foreach ($wardSpecificToles as $toleName) {
                    Tole::firstOrCreate([
                        'name' => $toleName,
                        'ward_id' => $ward->id,
                    ]);
                }
            } elseif ($ward->ward_no == 2) {
                $wardSpecificToles = [
                    'धोबीघाट',
                    'टेकु टोल',
                    'कालिमाटी',
                ];
                foreach ($wardSpecificToles as $toleName) {
                    Tole::firstOrCreate([
                        'name' => $toleName,
                        'ward_id' => $ward->id,
                    ]);
                }
            }
            // Add more ward-specific toles as needed
            */
        }

        $this->command->info('Mother Tongues, Castes, and Toles seeded successfully!');
    }
}