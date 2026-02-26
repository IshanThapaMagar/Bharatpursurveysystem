<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'slug' => 'superadmin',
                'translations' => [
                    ['locale' => 'en', 'name' => 'Superadmin'],
                    ['locale' => 'np', 'name' => 'सुपर एडमिन'],
                ],
            ],
            [
                'slug' => 'ward_admin',
                'translations' => [
                    ['locale' => 'en', 'name' => 'Ward Admin'],
                    ['locale' => 'np', 'name' => 'वडा एडमिन'],
                ],
            ],
            [
                'slug' => 'data_collector',
                'translations' => [
                    ['locale' => 'en', 'name' => 'Data Collector'],
                    ['locale' => 'np', 'name' => 'तथ्यांक संकलक'],
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            $role = \App\Models\Role::updateOrCreate(['slug' => $roleData['slug']]);
            foreach ($roleData['translations'] as $translation) {
                $role->translations()->updateOrCreate(
                    ['locale' => $translation['locale']],
                    ['name' => $translation['name']]
                );
            }
        }
    }
}
