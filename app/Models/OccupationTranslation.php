<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OccupationTranslation extends Model
{
    protected $fillable = ['occupation_id', 'locale', 'name'];

    public function occupation()
    {
        return $this->belongsTo(Occupation::class);
    }
}
