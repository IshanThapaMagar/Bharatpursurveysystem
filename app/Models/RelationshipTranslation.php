<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelationshipTranslation extends Model
{
    protected $fillable = ['relationship_id', 'locale', 'name'];

    public function relationship()
    {
        return $this->belongsTo(Relationship::class);
    }
}