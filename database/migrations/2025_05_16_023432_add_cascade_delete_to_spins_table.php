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
        Schema::table('spins', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['reward_id']);
            
            // Add the new foreign key with ON DELETE CASCADE
            $table->foreign('reward_id')
                  ->references('id')
                  ->on('spin_rewards')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spins', function (Blueprint $table) {
            // Drop the cascade delete foreign key
            $table->dropForeign(['reward_id']);
            
            // Restore original foreign key without cascade
            $table->foreign('reward_id')
                  ->references('id')
                  ->on('spin_rewards');
        });
    }
};
