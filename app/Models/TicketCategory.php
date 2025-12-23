<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'pertunjukan_id',
        'nama',
        'harga',
        'kuota',
        'kuota_tersisa',
        'deskripsi',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    public function pertunjukan()
    {
        return $this->belongsTo(Pertunjukan::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Get total sold tickets
    public function getTotalSoldAttribute()
    {
        return $this->kuota - $this->kuota_tersisa;
    }

    // Get total revenue for this category
    public function getTotalRevenueAttribute()
    {
        return $this->bookings()
            ->whereHas('transaction', function($query) {
                $query->where('status', 'paid');
            })
            ->sum('total_harga');
    }
}
