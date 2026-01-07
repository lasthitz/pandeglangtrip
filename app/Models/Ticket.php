<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'visit_date',
        'image_path',
        'is_active',
        'approval_status',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'is_active' => 'boolean',
        'price' => 'integer',
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
