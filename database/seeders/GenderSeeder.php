<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gender;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genders = [
            ['en' => 'Male', 'np' => 'पुरुष'],
            ['en' => 'Female', 'np' => 'महिला'],
            ['en' => 'Other', 'np' => 'अन्य'],
        ];

        foreach ($genders as $item) {
            $gender = Gender::create();
            $gender->translations()->createMany([
                ['locale' => 'en', 'name' => $item['en']],
                ['locale' => 'np', 'name' => $item['np']],
            ]);
        }
    }
}