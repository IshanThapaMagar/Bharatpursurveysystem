<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationLevelTranslation extends Model
{
    protected $fillable = ['education_level_id', 'locale', 'name'];

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }
}
