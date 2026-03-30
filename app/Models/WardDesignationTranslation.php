<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WardDesignationTranslation extends Model
{
    protected $fillable = ['ward_designation_id', 'locale', 'name'];

    public function wardDesignation(): BelongsTo
    {
        return $this->belongsTo(WardDesignation::class);
    }
}
