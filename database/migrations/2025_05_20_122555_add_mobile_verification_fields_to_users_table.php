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
        Schema::table('users', function (Blueprint $table) {
            // Add mobile verification fields if they don't exist yet
            if (!Schema::hasColumn('users', 'mobile_number')) {
                $table->string('mobile_number')->nullable()->after('is_telegram_user');
            }
            
            if (!Schema::hasColumn('users', 'preferred_otp_channel')) {
                $table->string('preferred_otp_channel')->default('sms')->after('mobile_number');
            }
            
            if (!Schema::hasColumn('users', 'is_mobile_verified')) {
                $table->boolean('is_mobile_verified')->default(false)->after('preferred_otp_channel');
            }
            
            if (!Schema::hasColumn('users', 'mobile_verified_at')) {
                $table->timestamp('mobile_verified_at')->nullable()->after('is_mobile_verified');
            }
            
            if (!Schema::hasColumn('users', 'mobile_verification_code')) {
                $table->string('mobile_verification_code')->nullable()->after('mobile_verified_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Don't drop columns in production as it could lead to data loss
            // Instead, we'll comment these out as a safety measure
            /*
            $table->dropColumn([
                'mobile_number',
                'preferred_otp_channel',
                'is_mobile_verified',
                'mobile_verified_at',
                'mobile_verification_code'
            ]);
            */
        });
    }
};
