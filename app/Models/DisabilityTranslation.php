<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisabilityTranslation extends Model
{
    protected $fillable = ['disability_id', 'locale', 'name'];

    public function disability()
    {
        return $this->belongsTo(Disability::class);
    }
}
