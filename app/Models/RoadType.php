<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoadType extends Model
{
    protected $with = ["translations"];

    protected $fillable = ['slug'];

    public function translations()
    {
        return $this->hasMany(RoadTypeTranslation::class);
    }

    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();
        return $translation ? $translation->name : ($this->translations->first()->name ?? null);
    }
}
