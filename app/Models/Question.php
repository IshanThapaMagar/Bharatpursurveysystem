<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'survey_section_id',
        'question_text',
        'question_subtext',
        'answer_required',
        'input_type_id',
        'option_group_id',
        'allow_multiple_option_answers',
        'order_index',
        'scale_from',
        'scale_to',
        'scale_label_low',
        'scale_label_high',
    ];
    
    protected $casts = [
        'answer_required' => 'boolean',
        'allow_multiple_option_answers' => 'boolean',
    ];

    public function surveySection(): BelongsTo
    {
        return $this->belongsTo(SurveySection::class);
    }

    public function inputType(): BelongsTo
    {
        return $this->belongsTo(InputType::class);
    }

    public function optionGroup(): BelongsTo
    {
        return $this->belongsTo(OptionGroup::class);
    }

    public function questionOptions(): HasMany
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index');
    }
}