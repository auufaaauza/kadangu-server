<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'qris' or 'bank_account'
            $table->string('name'); // Display name (e.g., "QRIS Kadangu", "BCA - Kadangu")
            $table->string('qris_image')->nullable(); // Path to QRIS image
            $table->string('bank_name')->nullable(); // Bank name (e.g., "BCA", "Mandiri")
            $table->string('account_number')->nullable(); // Account number
            $table->string('account_holder')->nullable(); // Account holder name
            $table->text('instructions')->nullable(); // Payment instructions
            $table->boolean('is_active')->default(true); // Active status
            $table->integer('display_order')->default(0); // Display order
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
