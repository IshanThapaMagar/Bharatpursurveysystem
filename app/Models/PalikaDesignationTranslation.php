<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PalikaDesignationTranslation extends Model
{
    protected $fillable = ['palika_designation_id', 'locale', 'name'];

    public function palikaDesignation()
    {
        return $this->belongsTo(PalikaDesignation::class);
    }
}
