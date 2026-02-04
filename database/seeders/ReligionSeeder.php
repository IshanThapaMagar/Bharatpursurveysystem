<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Religion;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $religions = [
            ['en' => 'Hindu', 'np' => 'हिन्दु'],
            ['en' => 'Buddhist', 'np' => 'बौद्ध'],
            ['en' => 'Islam', 'np' => 'इस्लाम'],
            ['en' => 'Kirat', 'np' => 'किँरात'],
            ['en' => 'Christian', 'np' => 'ईसाई'],
            ['en' => 'Other/Not Specified', 'np' => 'निर्दिष्ट / अन्य'],
        ];

        foreach ($religions as $item) {
            $religion = Religion::create();
            $religion->translations()->createMany([
                ['locale' => 'en', 'name' => $item['en']],
                ['locale' => 'np', 'name' => $item['np']],
            ]);
        }
    }
}