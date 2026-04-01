<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Response extends Model
{
    protected $fillable = [
        'user_id',
        'ward_id',
        'householder_id',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function householder(): BelongsTo
    {
        return $this->belongsTo(HouseHolder::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}