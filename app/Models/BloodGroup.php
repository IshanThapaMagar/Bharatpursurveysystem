<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodGroup extends Model
{
    protected $fillable = []; 

    public function translations()
    {
        return $this->hasMany(BloodGroupTranslation::class);
    }


    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();
        return $translation ? $translation->name : null;
    }
}
