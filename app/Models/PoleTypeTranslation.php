<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoleTypeTranslation extends Model
{
    protected $fillable = ['pole_type_id', 'locale', 'name'];

    public function poleType()
    {
        return $this->belongsTo(PoleType::class);
    }
}
