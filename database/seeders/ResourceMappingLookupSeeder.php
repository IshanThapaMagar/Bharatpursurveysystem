<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PoleType;
use App\Models\PoleTypeTranslation;
use App\Models\RoadType;
use App\Models\RoadTypeTranslation;
use App\Models\ToleDevelopmentOfficeType;
use App\Models\ToleDevelopmentOfficeTypeTranslation;

class ResourceMappingLookupSeeder extends Seeder
{
    public function run(): void
    {
        $poleTypes = [
            ['slug' => 'wood', 'translations' => ['en' => 'Wood', 'np' => 'काठ']],
            ['slug' => 'bamboo', 'translations' => ['en' => 'Bamboo', 'np' => 'बांस']],
            ['slug' => 'iron', 'translations' => ['en' => 'Iron', 'np' => 'फलाम']],
            ['slug' => 'cement', 'translations' => ['en' => 'Cement', 'np' => 'सिमेण्ट']],
        ];

        foreach ($poleTypes as $data) {
            $poleType = PoleType::firstOrCreate(['slug' => $data['slug']]);
            foreach ($data['translations'] as $locale => $name) {
                PoleTypeTranslation::updateOrCreate(
                    ['pole_type_id' => $poleType->id, 'locale' => $locale],
                    ['name' => $name]
                );
            }
        }

        $roadTypes = [
            ['slug' => 'pitch', 'translations' => ['en' => 'Pitch', 'np' => 'पिच']],
            ['slug' => 'gravel', 'translations' => ['en' => 'Gravel', 'np' => 'ग्राभेल']],
            ['slug' => 'dirt', 'translations' => ['en' => 'Dirt/Earthen', 'np' => 'कच्ची']],
            ['slug' => 'brick-printing', 'translations' => ['en' => 'Brick Printing', 'np' => 'ईट्टा छपाई']],
            ['slug' => 'concrete', 'translations' => ['en' => 'Concrete', 'np' => 'ढलान']],
            ['slug' => 'block-printing', 'translations' => ['en' => 'Block Printing', 'np' => 'ब्लक छपाई']],


        ];

        foreach ($roadTypes as $data) {
            $roadType = RoadType::firstOrCreate(['slug' => $data['slug']]);
            foreach ($data['translations'] as $locale => $name) {
                RoadTypeTranslation::updateOrCreate(
                    ['road_type_id' => $roadType->id, 'locale' => $locale],
                    ['name' => $name]
                );
            }
        }

        // Tole Development Office Types
        $officeTypes = [
            ['slug' => 'on-rent', 'translations' => ['en' => 'Office on rent', 'np' => 'कार्यालय भाडामा']],
            ['slug' => 'own-building', 'translations' => ['en' => 'Office in Own Building', 'np' => 'कार्यालय आफ्नै भवन भएको']],
            ['slug' => 'no-office', 'translations' => ['en' => 'No Office Available', 'np' => 'कार्यालयको व्यवस्था नभएको']],

        ];

        foreach ($officeTypes as $data) {
            $officeType = ToleDevelopmentOfficeType::firstOrCreate(['slug' => $data['slug']]);
            foreach ($data['translations'] as $locale => $name) {
                ToleDevelopmentOfficeTypeTranslation::updateOrCreate(
                    ['tole_dev_off_type_id' => $officeType->id, 'locale' => $locale],
                    ['name' => $name]
                );
            }
        }
    }
}
