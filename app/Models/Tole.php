<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Tole extends Model
{
    protected $fillable = ['name', 'ward_id'];

    protected static function booted()
    {
        $clearCache = function (Tole $tole) {
            Cache::forget("lookup_toles_ward_{$tole->ward_id}");
        };

        static::saved($clearCache);
        static::deleted($clearCache);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function householders(): HasMany
    {
        return $this->hasMany(Householder::class);
    }
}