<?php

namespace App\Models;

use Database\Factories\StrategicEventFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrategicEvent extends Model
{
    /** @use HasFactory<StrategicEventFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function casts(): array
    {
        return [
            'days' => 'array',
        ];
    }
}
