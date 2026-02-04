<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitOfMeasure extends Model
{
    protected $fillable = ['unit_of_measures_name'];

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}