<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pertunjukan_id',
        'talent_id',
    ];

    /**
     * Get the user who owns this wishlist
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the pertunjukan in this wishlist
     */
    public function pertunjukan()
    {
        return $this->belongsTo(Pertunjukan::class);
    }

    /**
     * Get the talent in this wishlist
     */
    public function talent()
    {
        return $this->belongsTo(Talent::class);
    }
}
