<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['slug'];

    public function translations()
    {
        return $this->hasMany(RoleTranslation::class);
    }

    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations()->where('locale', $locale)->first();
        return $translation ? $translation->name : $this->slug;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
