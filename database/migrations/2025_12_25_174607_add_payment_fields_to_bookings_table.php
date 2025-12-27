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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('status');
            $table->string('snap_token')->nullable()->after('payment_method');
            $table->string('payment_proof')->nullable()->after('snap_token');
            $table->string('payment_status')->default('unpaid')->after('payment_proof');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'snap_token', 'payment_proof', 'payment_status']);
        });
    }
};
