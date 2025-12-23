<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Talent extends Model
{
    use HasFactory;

    protected $table = 'talents';

    protected $fillable = [
        'seniman_id',
        'name',
        'slug',
        'bio',
        'genre',
        'base_price',
        'photo',
        'portfolio',
        'availability_status',
        'service_description',
        'status',
    ];

    protected $casts = [
        'portfolio' => 'array',
        'base_price' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($talent) {
            if (empty($talent->slug)) {
                $talent->slug = Str::slug($talent->name);
            }
        });

        static::updating(function ($talent) {
            if ($talent->isDirty('name')) {
                $talent->slug = Str::slug($talent->name);
            }
        });
    }

    // Relationships
    public function seniman()
    {
        return $this->belongsTo(Seniman::class);
    }

    public function packages()
    {
        return $this->hasMany(TalentPackage::class);
    }

    public function bookings()
    {
        return $this->hasMany(TalentBooking::class);
    }

    // Accessors
    public function getFormattedBasePriceAttribute()
    {
        return 'Rp ' . number_format($this->base_price, 0, ',', '.');
    }

    // Computed Properties
    public function getTotalBookingsAttribute()
    {
        return $this->bookings()->count();
    }

    public function getActivePackagesCountAttribute()
    {
        return $this->packages()->where('status', 'active')->count();
    }

    public function getPendingBookingsCountAttribute()
    {
        return $this->bookings()->where('status', 'pending')->count();
    }

    public function getCompletedBookingsCountAttribute()
    {
        return $this->bookings()->where('status', 'completed')->count();
    }
}
