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
        // Check if is_telegram_user doesn't exist, but telegram_id does
        // This would happen if the previous migration ran but didn't include is_telegram_user
        if (!Schema::hasColumn('users', 'is_telegram_user') && 
             Schema::hasColumn('users', 'telegram_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_telegram_user')->default(false)->after('telegram_username');
            });
        } 
        // If neither column exists, add all three
        else if (!Schema::hasColumn('users', 'telegram_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->bigInteger('telegram_id')->nullable()->unique()->after('id');
                $table->string('telegram_username')->nullable()->after('telegram_id');
                $table->boolean('is_telegram_user')->default(false)->after('telegram_username');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Only try to drop columns that exist
            if (Schema::hasColumn('users', 'is_telegram_user')) {
                $table->dropColumn('is_telegram_user');
            }
            
            if (Schema::hasColumn('users', 'telegram_username')) {
                $table->dropColumn('telegram_username');
            }
            
            if (Schema::hasColumn('users', 'telegram_id')) {
                $table->dropColumn('telegram_id');
            }
        });
    }
}; 