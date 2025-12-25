<?php

namespace Database\Seeders;

use App\Models\PaymentSetting;
use Illuminate\Database\Seeder;

class PaymentSettingSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create sample QRIS payment setting
        PaymentSetting::create([
            'type' => 'qris',
            'name' => 'QRIS Kadangu',
            'qris_image' => null, // Admin will upload this
            'instructions' => 'Scan QRIS code dan lakukan pembayaran. Upload bukti pembayaran setelah transfer.',
            'is_active' => true,
            'display_order' => 1,
        ]);

        // Create sample Bank Account payment settings
        PaymentSetting::create([
            'type' => 'bank_account',
            'name' => 'BCA - Kadangu',
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'PT Kadangu Indonesia',
            'instructions' => 'Transfer ke rekening BCA di atas. Pastikan nominal transfer sesuai dengan total pembayaran.',
            'is_active' => true,
            'display_order' => 2,
        ]);

        PaymentSetting::create([
            'type' => 'bank_account',
            'name' => 'Mandiri - Kadangu',
            'bank_name' => 'Mandiri',
            'account_number' => '0987654321',
            'account_holder' => 'PT Kadangu Indonesia',
            'instructions' => 'Transfer ke rekening Mandiri di atas. Konfirmasi pembayaran dengan upload bukti transfer.',
            'is_active' => true,
            'display_order' => 3,
        ]);
    }
}
