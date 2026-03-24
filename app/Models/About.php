<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'content',
        'image_1',
        'image_2',
        'experience_years',
        'tours_completed',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
