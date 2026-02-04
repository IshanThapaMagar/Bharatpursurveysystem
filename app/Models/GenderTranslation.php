<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenderTranslation extends Model
{
    protected $fillable = ['gender_id', 'locale', 'name'];

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }
}