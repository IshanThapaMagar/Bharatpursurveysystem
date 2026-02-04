<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialSkill extends Model
{
    protected $fillable = []; 

    public function translations()
    {
        return $this->hasMany(SpecialSkillTranslation::class);
    }


    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();
        return $translation ? $translation->name : null;
    }
}
