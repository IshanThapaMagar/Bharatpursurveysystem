<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tole;
use App\Models\Ward;

class TolesSeeder extends Seeder
{
    public function run(): void
    {
        $defaultToles = [
            'मुक्तीनगर टोल',
            'जनजागृती टोल',
            'नौरंगि टोल',
            'कमलादेबी टोल',
            'निलगिरी टोल',
            'पोखरी टोल',
            'नयाँकिरण टोल',
            'गाइखर्क टोल',
            'प्रगतिपथ टोल',
            'पारिजात टोल',
            'समन्वय टोल',
            'जनोदय टोल',
        ];

        $wardToles = [
            1 => $defaultToles,
        ];

        $wards = Ward::all();

        foreach ($wards as $ward) {
            $tolesToSeed = $wardToles[$ward->id] ?? $defaultToles;

            foreach ($tolesToSeed as $toleName) {
                Tole::firstOrCreate([
                    'name' => $toleName,
                    'ward_id' => $ward->id,
                ]);
            }
        }

        $this->command->info('Toles seeded successfully!');
    }
}