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
        Schema::table('referral_rewards', function (Blueprint $table) {
            $table->boolean('is_percentage_reward')->default(false)->after('coins_earned');
            $table->decimal('percentage_rate', 5, 2)->nullable()->after('is_percentage_reward');
            $table->string('source_activity')->nullable()->after('percentage_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referral_rewards', function (Blueprint $table) {
            $table->dropColumn(['is_percentage_reward', 'percentage_rate', 'source_activity']);
        });
    }
}; 