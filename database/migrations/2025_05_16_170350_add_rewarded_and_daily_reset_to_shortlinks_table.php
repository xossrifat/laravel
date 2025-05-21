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
            $table->boolean('rewarded')->default(false)->after('active')
                ->comment('If true, will send a reward email to users when they complete the shortlink');
            $table->boolean('daily_reset')->default(false)->after('rewarded')
                ->comment('If true, users can claim this shortlink daily, otherwise only once');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shortlinks', function (Blueprint $table) {
            $table->dropColumn('rewarded');
            $table->dropColumn('daily_reset');
        });
    }
};
