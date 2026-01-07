<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $fillable = [
        'name',
        'price_per_person',
        'description',
        'start_date',
        'end_date',
        'guide_name',
        'itinerary',
        'image_path',
        'is_active',
        'approval_status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'price_per_person' => 'integer',
    ];

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('approval_status', 'APPROVED');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 1);
    }
}
