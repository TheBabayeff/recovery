<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'customer_id',
        'qty',
        'price',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
    public function services()
    {
        return $this->belongsTo(Service::class);
    }
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
