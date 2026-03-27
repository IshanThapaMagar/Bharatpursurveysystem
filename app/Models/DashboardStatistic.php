<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DashboardStatistic extends Model
{
    protected $guarded = [];

    protected $casts = [
        'age_groups' => 'array',
        'gender_groups' => 'array',
        'citizenship_groups' => 'array',
        'mother_tongue_stats' => 'array',
        'caste_stats' => 'array',
        'education_stats' => 'array',
        'religion_stats' => 'array',
    ];
}
