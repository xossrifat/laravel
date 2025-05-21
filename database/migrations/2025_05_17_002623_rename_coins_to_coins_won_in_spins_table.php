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
        Schema::table('spins', function (Blueprint $table) {
            // Check if the coins column exists but coins_won doesn't
            if (Schema::hasColumn('spins', 'coins') && !Schema::hasColumn('spins', 'coins_won')) {
                $table->renameColumn('coins', 'coins_won');
            } 
            // If neither exists, add coins_won
            else if (!Schema::hasColumn('spins', 'coins') && !Schema::hasColumn('spins', 'coins_won')) {
                $table->integer('coins_won')->default(0)->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spins', function (Blueprint $table) {
            if (Schema::hasColumn('spins', 'coins_won') && !Schema::hasColumn('spins', 'coins')) {
                $table->renameColumn('coins_won', 'coins');
            }
        });
    }
};
