<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Reviews\App\Models\Review;

class Ticket extends Model
{
    protected $fillable = [
        'owner_id',
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

    // Bookings (polymorphic)
    public function bookings(): MorphMany
    {
        return $this->morphMany(\App\Models\Booking::class, 'bookable');
    }
}
