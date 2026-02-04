<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoolingPlaceTranslation extends Model
{
    protected $fillable = ['pooling_place_id', 'locale', 'name'];

    public function poolingPlace()
    {
        return $this->belongsTo(PoolingPlace::class);
    }
}
