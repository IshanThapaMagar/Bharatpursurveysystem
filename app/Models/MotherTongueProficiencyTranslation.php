<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotherTongueProficiencyTranslation extends Model
{
    protected $fillable = ['mother_tongue_proficiency_id', 'locale', 'name'];

    public function motherTongueProficiency()
    {
        return $this->belongsTo(MotherTongueProficiency::class);
    }
}
