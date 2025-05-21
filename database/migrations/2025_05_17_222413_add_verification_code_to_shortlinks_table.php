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
        Schema::table('shortlinks', function (Blueprint $table) {
            $table->string('verification_code')->nullable()->comment('The code user must enter to verify completion');
            $table->boolean('requires_verification')->default(true)->comment('Whether this shortlink requires code verification');
        });
        
        // Add verified column to the pivot table if it exists
        if (Schema::hasTable('shortlink_user')) {
            Schema::table('shortlink_user', function (Blueprint $table) {
                if (!Schema::hasColumn('shortlink_user', 'verified')) {
                    $table->boolean('verified')->default(false)->after('last_claimed_at')->comment('Whether the user has verified the code');
                }
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shortlinks', function (Blueprint $table) {
            $table->dropColumn('verification_code');
            $table->dropColumn('requires_verification');
        });
        
        if (Schema::hasTable('shortlink_user') && Schema::hasColumn('shortlink_user', 'verified')) {
            Schema::table('shortlink_user', function (Blueprint $table) {
                $table->dropColumn('verified');
        });
        }
    }
};
