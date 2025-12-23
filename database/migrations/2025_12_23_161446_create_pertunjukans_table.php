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
        Schema::create('pertunjukans', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi');
            $table->dateTime('tanggal_pertunjukan');
            $table->string('lokasi');
            $table->decimal('harga', 10, 2);
            $table->integer('kuota');
            $table->integer('kuota_tersisa');
            $table->string('gambar')->nullable();
            $table->foreignId('seniman_id')->constrained('senimans')->onDelete('cascade');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertunjukans');
    }
};
