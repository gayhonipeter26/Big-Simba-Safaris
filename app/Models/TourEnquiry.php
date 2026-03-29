<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourEnquiry extends Model
{
    protected $fillable = [
        'tour_id',
        'fleet_id',
        'user_id',
        'name',
        'email',
        'phone',
        'start_date',
        'end_date',
        'guests',
        'message',
        'status',
        'payment_status',
        'payment_method',
        'payment_reference',
        'paid_amount',
        'cancellation_reason',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function fleet(): BelongsTo
    {
        return $this->belongsTo(Fleet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
