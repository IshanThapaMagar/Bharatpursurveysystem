<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlaceTypeTranslation extends Model
{
    protected $fillable = ['place_type_id', 'locale', 'name'];

    public function placeType(): BelongsTo
    {
        return $this->belongsTo(PlaceType::class);
    }
}
