<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ImportantSite extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'place_name',
        'ward_id',
        'place_type_id',
        'place_description',
        'photo',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function placeType()
    {
        return $this->belongsTo(PlaceType::class, 'place_type_id');
    }

    public function deletePhoto()
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            Storage::disk('public')->delete($this->photo);
            $this->update(['photo' => null]);
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            if ($model->isForceDeleting() && $model->photo && Storage::disk('public')->exists($model->photo)) {
                Storage::disk('public')->delete($model->photo);
            }
        });
    }
}
