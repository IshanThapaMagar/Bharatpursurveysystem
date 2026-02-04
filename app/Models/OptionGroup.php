<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OptionGroup extends Model
{
    protected $fillable = ['option_group_name'];

    public function optionChoices(): HasMany
    {
        return $this->hasMany(OptionChoice::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}