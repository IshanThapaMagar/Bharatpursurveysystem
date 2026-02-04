<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionOption extends Model
{
    protected $fillable = ['question_id', 'option_choice_id'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function optionChoice(): BelongsTo
    {
        return $this->belongsTo(OptionChoice::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}