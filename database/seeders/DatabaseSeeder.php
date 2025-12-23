<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Seniman;
use App\Models\Pertunjukan;
use App\Models\Berita;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@kadangu.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create regular user
        User::create([
            'name' => 'User Test',
            'email' => 'user@kadangu.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create senimans
        $seniman1 = Seniman::create([
            'nama' => 'Ki Dalang Asep Sunandar',
            'bio' => 'Dalang wayang kulit profesional dengan pengalaman lebih dari 20 tahun',
            'kategori' => 'Wayang Kulit',
            'kontak' => '081234567890',
        ]);

        $seniman2 = Seniman::create([
            'nama' => 'Sanggar Tari Mekar Sari',
            'bio' => 'Sanggar tari tradisional yang fokus pada tarian Jawa dan Sunda',
            'kategori' => 'Tari Tradisional',
            'kontak' => '081234567891',
        ]);

        $seniman3 = Seniman::create([
            'nama' => 'Teater Koma',
            'bio' => 'Grup teater modern yang menggabungkan unsur tradisional dan kontemporer',
            'kategori' => 'Teater',
            'kontak' => '081234567892',
        ]);

        // Create pertunjukans
        Pertunjukan::create([
            'judul' => 'Wayang Kulit Ramayana',
            'deskripsi' => 'Pertunjukan wayang kulit dengan lakon Ramayana yang megah dan penuh makna',
            'tanggal_pertunjukan' => now()->addDays(15),
            'lokasi' => 'Gedung Kesenian Jakarta',
            'harga' => 150000,
            'kuota' => 200,
            'kuota_tersisa' => 200,
            'seniman_id' => $seniman1->id,
            'status' => 'active',
        ]);

        Pertunjukan::create([
            'judul' => 'Tari Saman Gayo',
            'deskripsi' => 'Pertunjukan tari Saman yang energik dan memukau dari Aceh',
            'tanggal_pertunjukan' => now()->addDays(20),
            'lokasi' => 'Taman Ismail Marzuki',
            'harga' => 100000,
            'kuota' => 150,
            'kuota_tersisa' => 150,
            'seniman_id' => $seniman2->id,
            'status' => 'active',
        ]);

        Pertunjukan::create([
            'judul' => 'Teater Musikal Mahabharata',
            'deskripsi' => 'Teater musikal modern dengan cerita Mahabharata yang dikemas apik',
            'tanggal_pertunjukan' => now()->addDays(30),
            'lokasi' => 'Teater Jakarta',
            'harga' => 250000,
            'kuota' => 300,
            'kuota_tersisa' => 300,
            'seniman_id' => $seniman3->id,
            'status' => 'active',
        ]);

        // Create berita
        Berita::create([
            'judul' => 'Pelestarian Seni Wayang di Era Digital',
            'konten' => 'Wayang kulit sebagai warisan budaya Indonesia terus beradaptasi dengan perkembangan zaman. Kini, pertunjukan wayang tidak hanya dapat dinikmati secara langsung, tetapi juga melalui platform digital...',
            'kategori' => 'Budaya',
            'penulis_id' => 1,
            'published_at' => now(),
        ]);

        Berita::create([
            'judul' => 'Festival Seni Tradisional 2025',
            'konten' => 'Festival seni tradisional tahun 2025 akan digelar dengan menampilkan berbagai pertunjukan dari seluruh nusantara. Acara ini bertujuan untuk melestarikan dan memperkenalkan seni tradisional kepada generasi muda...',
            'kategori' => 'Event',
            'penulis_id' => 1,
            'published_at' => now(),
        ]);
    }
}
