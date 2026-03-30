<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToleDevelopmentOfficeTypeTranslation extends Model
{
    protected $fillable = ['tole_dev_off_type_id', 'locale', 'name'];

    public function toleDevelopmentOfficeType()
    {
        return $this->belongsTo(ToleDevelopmentOfficeType::class, 'tole_dev_off_type_id');
    }
}
