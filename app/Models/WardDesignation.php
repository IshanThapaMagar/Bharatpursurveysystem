<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WardDesignation extends Model
{
    protected $with = ["translations"];

    protected $fillable = [];

    public function translations(): HasMany
    {
        return $this->hasMany(WardDesignationTranslation::class);
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
