<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class PalikaDesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designations = [
            [
                'np' => 'नगर प्रमुख',
                'en' => 'City chief',
            ],
            [
                'np' => 'नगर उपप्रमुख',
                'en' => 'City Deputy Chief',
            ],
            [
                'np' => 'प्रमुख प्रशासनिक अधिकृत',
                'en' => 'Chief Administrative Officer',
            ],
            [
                'np' => 'आईटि अफिसर',
                'en' => 'IT Officer',
            ],
        ];

        foreach ($designations as $designationData) {
            $designationId = DB::table('palika_designations')->insertGetId([
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('palika_designation_translations')->insert([
                [
                    'palika_designation_id' => $designationId,
                    'locale' => 'en',
                    'name' => $designationData['en'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'palika_designation_id' => $designationId,
                    'locale' => 'np',
                    'name' => $designationData['np'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}
