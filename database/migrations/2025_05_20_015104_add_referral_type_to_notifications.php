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
        // MySQL requires dropping and recreating the column to modify an ENUM
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('system', 'ban', 'promo', 'other', 'referral') NOT NULL DEFAULT 'system'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to the original ENUM values
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('system', 'ban', 'promo', 'other') NOT NULL DEFAULT 'system'");
    }
};
