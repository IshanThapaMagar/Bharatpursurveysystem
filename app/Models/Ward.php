<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ward extends Model
{
    protected $fillable = ['ward_no', 'name', 'location', 'description', 'contact_number', 'email', 'building_photo', 'latitude', 'longitude'];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function surveySections(): HasMany
    {
        return $this->hasMany(SurveySection::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(WardMember::class);
    }
}