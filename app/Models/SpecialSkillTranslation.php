<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialSkillTranslation extends Model
{
    protected $fillable = ['special_skill_id', 'locale', 'name'];

    public function specialSkill()
    {
        return $this->belongsTo(SpecialSkill::class);
    }
}
