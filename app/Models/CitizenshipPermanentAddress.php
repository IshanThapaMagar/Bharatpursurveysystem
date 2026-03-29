<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CitizenshipPermanentAddress extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected static function booted()
    {
        $clearCache = function () {
            Cache::forget('lookup_citizenship_addresses');
        };

        static::saved($clearCache);
        static::deleted($clearCache);
    }
}
