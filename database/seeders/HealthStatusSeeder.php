<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HealthStatus;

class HealthStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $healthStatuses = [
            ['en' => 'Normal Condition', 'np' => 'फिट अर फाइन तथा सामान्य'],
            ['en' => 'Heart Disease', 'np' => 'मुटु रोग'],
            ['en' => 'Asthma/Respiratory', 'np' => 'दम/श्वासप्रश्वास'],
            ['en' => 'Cancer', 'np' => 'क्यान्सर'],
            ['en' => 'Diabetes', 'np' => 'मधुमेह (डायवेटिज)'],
            ['en' => 'Kidney/Liver Disease', 'np' => 'मृगौला/कलेजोको रोग'],
            ['en' => 'Gynecological Disease', 'np' => 'स्त्री रोग'],
            ['en' => 'Ulcer/Intestinal Disease', 'np' => 'अल्सर/आन्द्राको रोग'],
            ['en' => 'High/Low Blood Pressure', 'np' => 'उच्च/निम्न रक्तचाप'],
            ['en' => 'Arthritis/Epilepsy/Parkinson\'s', 'np' => 'वाथ/इपिलेप्सी/पार्किन्सन'],
            ['en' => 'Mental Health Disorder', 'np' => 'सू/अल्काइमर्स'],
            ['en' => 'Other Chronic Diseases', 'np' => 'अन्य दीर्घरोग'],
            ['en' => 'Don\'t Know', 'np' => 'थाहा नभएको'],
            ['en' => 'Health Status Not Specified', 'np' => 'स्वास्थ्य अवस्था'],
        ];

        foreach ($healthStatuses as $item) {
            $healthStatus = HealthStatus::create();
            $healthStatus->translations()->createMany([
                ['locale' => 'en', 'name' => $item['en']],
                ['locale' => 'np', 'name' => $item['np']],
            ]);
        }
    }
}
