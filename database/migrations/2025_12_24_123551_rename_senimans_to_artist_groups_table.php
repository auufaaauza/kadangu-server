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
        // Rename table
        Schema::rename('senimans', 'artist_groups');
        
        // Update foreign key column names in related tables
        Schema::table('talents', function (Blueprint $table) {
            $table->renameColumn('seniman_id', 'artist_group_id');
        });
        
        Schema::table('pertunjukans', function (Blueprint $table) {
            $table->renameColumn('seniman_id', 'artist_group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse foreign key column names
        Schema::table('talents', function (Blueprint $table) {
            $table->renameColumn('artist_group_id', 'seniman_id');
        });
        
        Schema::table('pertunjukans', function (Blueprint $table) {
            $table->renameColumn('artist_group_id', 'seniman_id');
        });
        
        // Rename table back
        Schema::rename('artist_groups', 'senimans');
    }
};
