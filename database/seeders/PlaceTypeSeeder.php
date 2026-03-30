<?php

namespace Database\Seeders;

use App\Models\PlaceType;
use App\Models\PlaceTypeTranslation;
use Illuminate\Database\Seeder;

class PlaceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $placeTypes = [
            [
                'en' => 'Tourist Site',
                'np' => 'पर्यटकिय स्थल',
            ],
            [
                'en' => 'Monastery/Temple',
                'np' => 'मठमन्दिर',
            ],
            [
                'en' => 'Religious Organizations (Guthi, Mosque, Church, etc.)',
                'np' => 'धार्मिक संघसंस्था (गुठी, मस्जिद, चर्च आदि)',
            ],
            [
                'en' => 'Public Park, Open Ground & Vacant Land',
                'np' => 'सार्वजनिक पार्क, चौर तथा खाली स्थल',
            ],
            [
                'en' => 'Public Pond',
                'np' => 'सार्वजनिक पोखरी',
            ],
            [
                'en' => 'Public Well/Water Source/Tap',
                'np' => 'सार्वजनिक इनार/पँधेरी/धारा',
            ],
            [
                'en' => 'Stupa/Statue',
                'np' => 'स्तुपा/मूर्ती',
            ],
            [
                'en' => 'Educational Institution (School, College, Library)',
                'np' => 'शैक्षिक संस्था (विद्यालय, महाविद्यालय तथा पुस्तकालय)',
            ],
            [
                'en' => 'Business',
                'np' => 'व्यवसाय',
            ],
            [
                'en' => 'Government Office',
                'np' => 'सरकारी कार्यालय',
            ],
            [
                'en' => 'Cremation/Burial Site',
                'np' => 'घाट/मसान/चिहान',
            ],
            [
                'en' => 'Financial Institution (Bank, Cooperative, Finance)',
                'np' => 'वित्तीय संस्था (बैंक, सहकारी तथा फाइनान्स)',
            ],
            [
                'en' => 'NGO/Club',
                'np' => 'गैर सरकारी संस्था/क्लब',
            ],
            [
                'en' => 'Consumer Committee',
                'np' => 'उपभोक्ता समिति',
            ],
            [
                'en' => 'Health Institution & Center',
                'np' => 'स्वास्थ्य संस्था तथा केन्द्र',
            ],
            [
                'en' => 'Public Forest',
                'np' => 'सार्वजनिक वनजंगल',
            ],
        ];

        foreach ($placeTypes as $type) {
            $placeType = PlaceType::create();

            PlaceTypeTranslation::create([
                'place_type_id' => $placeType->id,
                'locale' => 'en',
                'name' => $type['en'],
            ]);

            PlaceTypeTranslation::create([
                'place_type_id' => $placeType->id,
                'locale' => 'np',
                'name' => $type['np'],
            ]);
        }
    }
}
