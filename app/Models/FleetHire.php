<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FleetHire extends Model
{
    use HasFactory;

    protected $fillable = [
        'fleet_id',
        'user_id',
        'name',
        'email',
        'phone',
        'start_date',
        'end_date',
        'message',
        'status',
        'payment_status',
        'payment_method',
        'payment_reference',
        'payment_id',
        'paid_amount',
    ];

    public function fleet(): BelongsTo
    {
        return $this->belongsTo(Fleet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
