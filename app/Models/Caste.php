<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Caste extends Model
{
    protected $fillable = ['name'];

    public function householders(): HasMany
    {
        return $this->hasMany(Householder::class);
    }
}