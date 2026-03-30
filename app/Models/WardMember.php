<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WardMember extends Model
{
    protected $fillable = ['ward_id', 'ward_designation_id', 'name', 'photo'];

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(WardDesignation::class, 'ward_designation_id');
    }
}
