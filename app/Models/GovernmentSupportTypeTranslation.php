<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GovernmentSupportTypeTranslation extends Model
{
    protected $fillable = ['government_support_type_id', 'locale', 'name'];

    public function governmentSupportType()
    {
        return $this->belongsTo(GovernmentSupportType::class);
    }
}
