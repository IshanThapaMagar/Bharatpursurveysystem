<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PalikaAdmin extends Model
{
    protected $fillable = ['name', 'designation_id', 'email', 'phone', 'photo'];

    public function designation()
    {
        return $this->belongsTo(PalikaDesignation::class, 'designation_id');
    }
}
