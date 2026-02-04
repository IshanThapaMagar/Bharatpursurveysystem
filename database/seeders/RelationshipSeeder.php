<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Relationship;

class RelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $relationships = [
            ['en' => 'Self', 'np' => 'आफु'],
            ['en' => 'Grandfather', 'np' => 'हजुर बुबा'],
            ['en' => 'Grandmother', 'np' => 'हजुर आमा'],
            ['en' => 'Father', 'np' => 'बुबा'],
            ['en' => 'Mother', 'np' => 'आमा'],
            ['en' => 'Husband', 'np' => 'श्रीमान'],
            ['en' => 'Wife', 'np' => 'श्रीमती'],
            ['en' => 'Son', 'np' => 'छोरा'],
            ['en' => 'Daughter', 'np' => 'छोरी'],
            ['en' => 'Grandson', 'np' => 'नाति'],
            ['en' => 'Granddaughter', 'np' => 'नातिनी'],
            ['en' => 'Brother (Elder)', 'np' => 'दाई'],
            ['en' => 'Brother (Younger)', 'np' => 'भाई'],
            ['en' => 'Sister (Elder)', 'np' => 'दिदी'],
            ['en' => 'Sister (Younger)', 'np' => 'बहिनी'],
            ['en' => 'Daughter-in-law', 'np' => 'बुहारी'],
            ['en' => 'Son-in-law', 'np' => 'ज्वाँइ'],
            ['en' => 'Sister-in-law', 'np' => 'भाउजु'],
            ['en' => 'Nephew (Maternal)', 'np' => 'भान्जा'],
            ['en' => 'Niece (Maternal)', 'np' => 'भान्जी'],
            ['en' => 'Nephew (Paternal)', 'np' => 'भतिजा'],
            ['en' => 'Niece (Paternal)', 'np' => 'भतिजी'],
            ['en' => 'Mother-in-law', 'np' => 'सासु'],
            ['en' => 'Father-in-law', 'np' => 'ससुरा'],
            ['en' => 'Other', 'np' => 'अन्य'],
        ];

        foreach ($relationships as $item) {
            $relationship = Relationship::create();
            $relationship->translations()->createMany([
                ['locale' => 'en', 'name' => $item['en']],
                ['locale' => 'np', 'name' => $item['np']],
            ]);
        }
    }
}