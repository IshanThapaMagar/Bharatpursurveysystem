<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodGroupTranslation extends Model
{
    protected $fillable = ['blood_group_id', 'locale', 'name'];

    public function bloodGroup()
    {
        return $this->belongsTo(BloodGroup::class);
    }
}
