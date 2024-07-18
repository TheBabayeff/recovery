<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'total_price',
        'status',
        'finished_at',
        'note',
        'engineer_note',
        'device_model',
        'device_serial_number',
        'device_appearance',
        'operator_id',
        'engineer_id',
        'customer_id',
    ];

    protected $casts = [
        'status' => TicketStatus::class,
    ];
    // Relations to users
    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function engineer()
    {
        return $this->belongsTo(User::class, 'engineer_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ServiceItem::class, 'ticket_id');
    }
    public function diagnostic(): BelongsTo
    {
        return $this->belongsTo(Diagnostic::class, 'diagnostic_id');
    }

    public function getCustomerNameAttribute()
    {
        return $this->customer ? $this->customer->name : 'No Customer';
    }

    public function getDiagnosticPriceAttribute()
    {
        return $this->diagnostic ? $this->diagnostic->price : 0;
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
