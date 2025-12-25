<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ArtistGroup;
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

        // Seed payment settings
        $this->call(PaymentSettingSeeder::class);
    }
}
