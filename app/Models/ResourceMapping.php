<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ResourceMapping extends Model
{
    protected $fillable = [
        'ward_id',
        'tole_id',
        'electricity_pole_number',
        'tole_dev_office_type_id',
        'nala_nikash',
    ];

    protected $casts = [
        'nala_nikash' => 'boolean',
    ];

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function tole(): BelongsTo
    {
        return $this->belongsTo(Tole::class);
    }

    public function toleDevelopmentOfficeType(): BelongsTo
    {
        return $this->belongsTo(ToleDevelopmentOfficeType::class, 'tole_dev_office_type_id');
    }

    public function poleTypes(): BelongsToMany
    {
        return $this->belongsToMany(PoleType::class, 'resource_mapping_pole_types')
            ->withPivot('quantity');
    }

    public function roadTypes(): BelongsToMany
    {
        return $this->belongsToMany(RoadType::class, 'resource_mapping_road_types')
            ->withPivot('length');
    }
}
