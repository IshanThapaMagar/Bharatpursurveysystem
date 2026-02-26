<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HouseHolder extends Model
{
    protected $fillable = [
        'householder_name',
        'father_name',
        'mother_name',
        'mother_tongue_id',
        'caste_id',
        'tole_id',
        'ward_no',
        'lot_number',
        'house_number',
        'phone_number',
        'citizenship_permanent_address_id',
        'profile_photo',
    ];

    public function motherTongue(): BelongsTo
    {
        return $this->belongsTo(MotherTongue::class);
    }

    public function caste(): BelongsTo
    {
        return $this->belongsTo(Caste::class);
    }

    public function tole(): BelongsTo
    {
        return $this->belongsTo(Tole::class);
    }

    public function citizenshipPermanentAddress(): BelongsTo
    {
        return $this->belongsTo(CitizenshipPermanentAddress::class, 'citizenship_permanent_address_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(HouseMember::class, 'house_holder_id');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }
}