<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertunjukan extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi',
        'tanggal_pertunjukan',
        'lokasi',
        'harga',
        'biaya_layanan',
        'ppn',
        'kuota',
        'kuota_tersisa',
        'gambar',
        'artist_group_id',
        'status',
    ];

    protected $casts = [
        'tanggal_pertunjukan' => 'datetime',
        'harga' => 'decimal:2',
    ];

    /**
     * Get the artist group for this pertunjukan
     */
    public function artistGroup()
    {
        return $this->belongsTo(ArtistGroup::class, 'artist_group_id');
    }

    /**
     * Get all ticket categories for this pertunjukan
     */
    public function ticketCategories()
    {
        return $this->hasMany(TicketCategory::class);
    }

    /**
     * Get all bookings for this pertunjukan
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get users who wishlisted this pertunjukan
     */
    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    /**
     * Get total tickets sold
     */
    public function getTotalSoldAttribute()
    {
        return $this->kuota - $this->kuota_tersisa;
    }

    /**
     * Get total revenue (paid bookings only)
     */
    public function getTotalRevenueAttribute()
    {
        return $this->bookings()
            ->whereHas('transaction', function($query) {
                $query->where('status', 'paid');
            })
            ->sum('total_harga');
    }

    /**
     * Get total bookings count
     */
    public function getTotalBookingsAttribute()
    {
        return $this->bookings()->count();
    }
}
