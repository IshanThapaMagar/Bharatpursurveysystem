<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlaceType extends Model
{
    protected $with = ["translations"];

    protected $fillable = [];

    public function translations(): HasMany
    {
        return $this->hasMany(PlaceTypeTranslation::class);
    }

    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();
        return $translation ? $translation->name : null;
    }
}
