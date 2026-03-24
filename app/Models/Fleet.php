<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'capacity',
        'drive_type',
        'features',
        'is_available',
        'drivers',
        'suited_for',
        'availability',
        'price_per_hour',
        'price_per_day',
        'price_per_safari_drive',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price_per_hour' => 'decimal:2',
        'price_per_day' => 'decimal:2',
        'price_per_safari_drive' => 'decimal:2',
    ];

    public function getFeaturesArrayAttribute(): array
    {
        return $this->features ? array_map('trim', explode(',', $this->features)) : [];
    }
}
