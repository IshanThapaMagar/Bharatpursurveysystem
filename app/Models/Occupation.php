<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    protected $with = ["translations"];

    protected $fillable = ['is_active'];

    public function translations()
    {
        return $this->hasMany(OccupationTranslation::class);
    }

    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first()
            ?: $this->translations->where('locale', 'np')->first();
        return $translation ? $translation->name : null;
    }
}
