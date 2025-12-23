<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'talent_id',
        'name',
        'price',
        'duration_hours',
        'description',
        'includes',
        'status',
    ];

    protected $casts = [
        'includes' => 'array',
        'price' => 'integer',
        'duration_hours' => 'integer',
    ];

    // Relationships
    public function talent()
    {
        return $this->belongsTo(Talent::class);
    }

    public function bookings()
    {
        return $this->hasMany(TalentBooking::class, 'package_id');
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedDurationAttribute()
    {
        return $this->duration_hours . ' jam';
    }
}
