<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoadTypeTranslation extends Model
{
    protected $fillable = ['road_type_id', 'locale', 'name'];

    public function roadType()
    {
        return $this->belongsTo(RoadType::class);
    }
}
