<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtistGroupSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $categories = [
            [
                'nama' => 'Musik',
                'bio' => 'Seniman dan grup musik dari berbagai genre',
                'kategori' => 'musik',
            ],
            [
                'nama' => 'Tari',
                'bio' => 'Penari dan koreografer tradisional maupun kontemporer',
                'kategori' => 'tari',
            ],
            [
                'nama' => 'Teater',
                'bio' => 'Aktor, aktris, dan grup teater',
                'kategori' => 'teater',
            ],
            [
                'nama' => 'Seni Rupa',
                'bio' => 'Pelukis, pematung, dan seniman visual',
                'kategori' => 'seni-rupa',
            ],
            [
                'nama' => 'Sastra',
                'bio' => 'Penulis, penyair, dan sastrawan',
                'kategori' => 'sastra',
            ],
            [
                'nama' => 'Film',
                'bio' => 'Sutradara, sinematografer, dan filmmaker',
                'kategori' => 'film',
            ],
            [
                'nama' => 'Budaya',
                'bio' => 'Pelestari budaya dan seni tradisional',
                'kategori' => 'budaya',
            ],
            [
                'nama' => 'Workshop',
                'bio' => 'Instruktur dan fasilitator workshop seni',
                'kategori' => 'workshop',
            ],
        ];

        foreach ($categories as $category) {
            // Check if category already exists
            $exists = DB::table('artist_groups')
                ->where('kategori', $category['kategori'])
                ->exists();
            
            if (!$exists) {
                DB::table('artist_groups')->insert([
                    'nama' => $category['nama'],
                    'bio' => $category['bio'],
                    'kategori' => $category['kategori'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
