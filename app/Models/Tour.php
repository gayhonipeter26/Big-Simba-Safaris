<?php

namespace App\Models;

use Database\Factories\TourFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tour extends Model
{
    /** @use HasFactory<TourFactory> */
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'name',
        'slug',
        'price',
        'duration',
        'itinerary',
        'images',
        'whats_included',
        'whats_excluded',
    ];

    protected function casts(): array
    {
        return [
            'itinerary' => 'array',
            'images' => 'array',
            'whats_included' => 'array',
            'whats_excluded' => 'array',
            'price' => 'decimal:2',
        ];
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
