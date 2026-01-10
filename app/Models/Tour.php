<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Reviews\app\Models\Review;

class Tour extends Model
{
    protected $fillable = [
        'owner_id',
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

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('approval_status', 'APPROVED');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 1);
    }

    // T7: Reviews (polymorphic)
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable')->latest();
    }
}
