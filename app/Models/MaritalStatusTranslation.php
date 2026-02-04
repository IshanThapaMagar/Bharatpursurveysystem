<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaritalStatusTranslation extends Model
{
    protected $fillable = ['marital_status_id', 'locale', 'name'];

    public function maritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class);
    }
}
