<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pertunjukan_id',
        'ticket_category_id',
        'jumlah_tiket',
        'total_harga',
        'status',
        'payment_method',
        'snap_token',
        'kode_booking',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
    ];

    /**
     * Generate unique booking code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->kode_booking)) {
                $booking->kode_booking = 'BK-' . strtoupper(Str::random(8));
            }
        });
    }

    /**
     * Get the user who made this booking
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the pertunjukan for this booking
     */
    public function pertunjukan()
    {
        return $this->belongsTo(Pertunjukan::class);
    }

    /**
     * Get the ticket category for this booking
     */
    public function ticketCategory()
    {
        return $this->belongsTo(TicketCategory::class);
    }

    /**
     * Get the transaction for this booking
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
