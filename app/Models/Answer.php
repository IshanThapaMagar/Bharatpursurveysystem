<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    protected $fillable = [
        'response_id',
        'question_option_id',
        'answer_numeric',
        'answer_text',
        'custom_input_value',
        'unit_of_measure_id',
    ];

    protected $casts = [
        'answer_numeric' => 'decimal:2',
    ];

    public function response(): BelongsTo
    {
        return $this->belongsTo(Response::class);
    }

    public function questionOption(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class);
    }

    public function unitOfMeasure(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasure::class);
    }
}