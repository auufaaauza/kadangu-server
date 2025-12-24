<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistGroup extends Model
{
    use HasFactory;

    protected $table = 'artist_groups';

    protected $fillable = [
        'nama',
        'bio',
        'foto',
        'kategori',
        'kontak',
        'genre',
    ];

    /**
     * Get all pertunjukans by this artist group
     */
    public function pertunjukans()
    {
        return $this->hasMany(Pertunjukan::class, 'artist_group_id');
    }

    /**
     * Get all talents by this artist group
     */
    public function talents()
    {
        return $this->hasMany(Talent::class, 'artist_group_id');
    }
}
