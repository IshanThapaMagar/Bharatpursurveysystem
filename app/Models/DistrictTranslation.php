<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistrictTranslation extends Model
{
    protected $fillable = ['district_id', 'locale', 'name'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
