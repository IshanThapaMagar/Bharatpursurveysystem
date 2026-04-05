<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationLevel extends Model
{
    protected $with = ["translations"];

    protected $fillable = []; 

    public function translations()
    {
        return $this->hasMany(EducationLevelTranslation::class);
    }


    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();
        return $translation ? $translation->name : null;
    }
}
