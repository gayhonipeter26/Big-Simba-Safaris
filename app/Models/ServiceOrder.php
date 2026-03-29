<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
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

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
