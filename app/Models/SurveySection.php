<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveySection extends Model
{
    protected $fillable = ['title', 'description', 'ward_id', 'order_index'];
    
    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index');
    }

    public function scopeForWard($query, $wardId)
    {
        return $query->where('ward_id', $wardId);
    }
}