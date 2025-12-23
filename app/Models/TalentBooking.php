<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TalentBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'talent_id',
        'package_id',
        'event_date',
        'event_time',
        'event_location',
        'event_details',
        'total_price',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'event_date' => 'date',
        'total_price' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = self::generateBookingCode();
            }
        });
    }

    private static function generateBookingCode()
    {
        $date = Carbon::now()->format('Ymd');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        return 'TB-' . $date . '-' . $random;
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function talent()
    {
        return $this->belongsTo(Talent::class);
    }

    public function package()
    {
        return $this->belongsTo(TalentPackage::class, 'package_id');
    }

    // Accessors
    public function getFormattedTotalPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function getFormattedEventDateAttribute()
    {
        return $this->event_date->format('d F Y');
    }

    public function getFormattedEventTimeAttribute()
    {
        return Carbon::parse($this->event_time)->format('H:i');
    }
}
