<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertunjukan_id')->constrained()->onDelete('cascade');
            $table->string('nama'); // VIP, Regular, Student, dll
            $table->decimal('harga', 10, 2);
            $table->integer('kuota');
            $table->integer('kuota_tersisa');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_categories');
    }
};
