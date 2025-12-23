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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pertunjukan_id')->constrained('pertunjukans')->onDelete('cascade');
            $table->integer('jumlah_tiket');
            $table->decimal('total_harga', 10, 2);
            $table->enum('status', ['pending', 'paid', 'confirmed', 'cancelled'])->default('pending');
            $table->string('kode_booking')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
