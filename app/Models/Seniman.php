<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seniman extends Model
{
    use HasFactory;

    protected $table = 'senimans';

    protected $fillable = [
        'nama',
        'bio',
        'foto',
        'kategori',
        'kontak',
        'genre',
    ];

    /**
     * Get all pertunjukans by this seniman
     */
    public function pertunjukans()
    {
        return $this->hasMany(Pertunjukan::class);
    }

    /**
     * Get all talents by this seniman
     */
    public function talents()
    {
        return $this->hasMany(Talent::class);
    }
}
