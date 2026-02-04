<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WardNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wards = [
            [
                    'ward_no'=>10
            ],
            [
                    'ward_no'=>15
            ],
            [
                    'ward_no'=>20
            ],
            [
                    'ward_no'=>21
            ],
            [
                    'ward_no'=>23
            ],
            

        ];

        DB::table('wards')->insert($wards);
    }
}