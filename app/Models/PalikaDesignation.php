<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PalikaDesignation extends Model
{
    protected $fillable = [];

    public function translations()
    {
        return $this->hasMany(PalikaDesignationTranslation::class);
    }

    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();
        
        if (!$translation) {
            $translation = $this->translations->first();
        }

        return $translation->name ?? null;
    }
}
