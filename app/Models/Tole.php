<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tole extends Model
{
    protected $fillable = ['name', 'ward_id'];

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function householders(): HasMany
    {
        return $this->hasMany(Householder::class);
    }
}