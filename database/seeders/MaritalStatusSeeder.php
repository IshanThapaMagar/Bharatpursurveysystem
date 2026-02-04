<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MaritalStatus;

class MaritalStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $maritalStatuses = [
            ['en' => 'Married', 'np' => 'विवाहित'],
            ['en' => 'Single', 'np' => 'अविवाहित'],
            ['en' => 'Single Woman', 'np' => 'एकल महिला'],
            ['en' => 'Single Man', 'np' => 'एकल पुरुष'],
        ];

        foreach ($maritalStatuses as $item) {
            $maritalStatus = MaritalStatus::create();
            $maritalStatus->translations()->createMany([
                ['locale' => 'en', 'name' => $item['en']],
                ['locale' => 'np', 'name' => $item['np']],
            ]);
        }
    }
}
