<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InputType extends Model
{
    protected $fillable = ['input_type_name'];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}