<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthStatusTranslation extends Model
{
    protected $fillable = ['health_status_id', 'locale', 'name'];

    public function healthStatus()
    {
        return $this->belongsTo(HealthStatus::class);
    }
}
