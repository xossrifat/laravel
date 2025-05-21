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
        Schema::create('shortlink_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('shortlink_id')->constrained()->onDelete('cascade');
            $table->timestamp('last_claimed_at')->nullable();
            $table->timestamps();
            
            // Make sure a user can only claim a specific shortlink once per day
            $table->unique(['user_id', 'shortlink_id', 'last_claimed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shortlink_user');
    }
};
