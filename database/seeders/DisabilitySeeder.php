<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Disability;

class DisabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $disabilities = [
            ['en' => 'Differently Abled', 'np' => 'अपाङ्गता'],
            ['en' => 'Physical', 'np' => 'शारीरिक'],
            ['en' => 'Sight Related', 'np' => 'दृष्टि सम्बन्धी'],
            ['en' => 'Hearing Related', 'np' => 'सुनाइ सम्बन्धी'],
            ['en' => 'Voice Related', 'np' => 'स्वर/बोलाइ सम्बन्धी'],
            ['en' => 'Mental', 'np' => 'मानसिक'],
            ['en' => 'Multiple Disabilities', 'np' => 'बहुअपाङ्गता'],
            ['en' => 'No', 'np' => 'छैन'],
        ];

        foreach ($disabilities as $item) {
            $disability = Disability::create();
            $disability->translations()->createMany([
                ['locale' => 'en', 'name' => $item['en']],
                ['locale' => 'np', 'name' => $item['np']],
            ]);
        }
    }
}
