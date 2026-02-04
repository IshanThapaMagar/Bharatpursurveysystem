<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OptionChoice extends Model
{
    protected $fillable = ['option_group_id', 'choice_text'];

    public function optionGroup(): BelongsTo
    {
        return $this->belongsTo(OptionGroup::class);
    }

    public function questionOptions(): HasMany
    {
        return $this->hasMany(QuestionOption::class);
    }
}