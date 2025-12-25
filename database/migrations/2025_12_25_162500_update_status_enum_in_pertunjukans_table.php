<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Using raw SQL to modify ENUM column as it's the most reliable way for MySQL
        DB::statement("ALTER TABLE pertunjukans MODIFY COLUMN status ENUM('active', 'inactive', 'passed', 'coming_soon') NOT NULL DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting back to previous state (assuming 'passed' was intended to be there, or at least active/inactive)
        // If we strictly revert to original migration it would be just active/inactive
        DB::statement("ALTER TABLE pertunjukans MODIFY COLUMN status ENUM('active', 'inactive', 'passed') NOT NULL DEFAULT 'active'");
    }
};
