<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class ,
            SuperAdminSeeder::class ,
            WardNumberSeeder::class ,
            GenderSeeder::class ,
            ReligionSeeder::class ,
            BloodGroupSeeder::class ,
            DisabilitySeeder::class ,
            DistrictSeeder::class ,
            EducationLevelSeeder::class ,
            GovernmentSupportTypeSeeder::class ,
            HealthStatusSeeder::class ,
            InstitutionTypeSeeder::class ,
            MaritalStatusSeeder::class ,
            MotherTongueProficiencySeeder::class ,
            RelationshipSeeder::class ,
            SpecialSkillSeeder::class ,
            PoolingPlaceSeeder::class ,
            LookupTablesSeeder::class ,
            PalikaDesignationSeeder::class ,

        ]);
    }
}