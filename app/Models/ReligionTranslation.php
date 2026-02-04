<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReligionTranslation extends Model
{
    protected $fillable = ['religion_id', 'locale', 'name'];

    public function religion()
    {
        return $this->belongsTo(Religion::class);
    }
}
