<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class MotherTongue extends Model
{
    protected $fillable = ['name'];

    protected static function booted()
    {
        $clearCache = function () {
            Cache::forget('lookup_mother_tongues');
        };

        static::saved($clearCache);
        static::deleted($clearCache);
    }

    public function householders(): HasMany
    {
        return $this->hasMany(Householder::class);
    }
}