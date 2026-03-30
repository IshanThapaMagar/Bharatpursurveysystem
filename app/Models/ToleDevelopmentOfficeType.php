<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToleDevelopmentOfficeType extends Model
{
    protected $fillable = ['slug'];

    public function translations()
    {
        return $this->hasMany(ToleDevelopmentOfficeTypeTranslation::class, 'tole_dev_off_type_id');
    }

    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();
        return $translation ? $translation->name : ($this->translations->first()->name ?? null);
    }
}
