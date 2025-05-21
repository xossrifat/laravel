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
            $table->string('mobile_number')->nullable()->after('email');
            $table->boolean('is_mobile_verified')->default(false)->after('mobile_number');
            $table->timestamp('mobile_verified_at')->nullable()->after('is_mobile_verified');
            $table->string('mobile_verification_code')->nullable()->after('mobile_verified_at');
            $table->string('preferred_otp_channel')->default('sms')->after('mobile_verification_code'); // sms or whatsapp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('mobile_number');
            $table->dropColumn('is_mobile_verified');
            $table->dropColumn('mobile_verified_at');
            $table->dropColumn('mobile_verification_code');
            $table->dropColumn('preferred_otp_channel');
        });
    }
}; 