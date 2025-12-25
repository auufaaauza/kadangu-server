<?php

namespace Database\Seeders;

use App\Models\ArtistGroup;
use Illuminate\Database\Seeder;

class TalentCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Musik',
            'Tari',
            'Teater',
            'Seni Rupa',
            'Sastra',
            'Film',
            'Budaya',
        ];

        foreach ($categories as $category) {
            ArtistGroup::firstOrCreate(
                ['nama' => $category],
                [
                    'bio' => 'Kategori Talent ' . $category,
                    'kategori' => $category,
                    'kontak' => '-'
                ]
            );
        }
    }
}
