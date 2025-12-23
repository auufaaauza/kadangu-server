<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'jumlah',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status',
        'tanggal_bayar',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_bayar' => 'datetime',
    ];

    /**
     * Get the booking for this transaction
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user who made this transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
