<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BloodGroup;

class BloodGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bloodGroups = [
            ['en' => 'A+', 'np' => 'A+'],
            ['en' => 'A-', 'np' => 'A-'],
            ['en' => 'B+', 'np' => 'B+'],
            ['en' => 'B-', 'np' => 'B-'],
            ['en' => 'AB+', 'np' => 'AB+'],
            ['en' => 'AB-', 'np' => 'AB-'],
            ['en' => 'O+', 'np' => 'O+'],
            ['en' => 'O-', 'np' => 'O-'],
            ['en' => 'Don\'t Know', 'np' => 'थाहा नभएको'],
        ];

        foreach ($bloodGroups as $item) {
            $bloodGroup = BloodGroup::create();
            $bloodGroup->translations()->createMany([
                ['locale' => 'en', 'name' => $item['en']],
                ['locale' => 'np', 'name' => $item['np']],
            ]);
        }
    }
}