<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionTypeTranslation extends Model
{
    protected $fillable = ['institution_type_id', 'locale', 'name'];

    public function institutionType()
    {
        return $this->belongsTo(InstitutionType::class);
    }
}
