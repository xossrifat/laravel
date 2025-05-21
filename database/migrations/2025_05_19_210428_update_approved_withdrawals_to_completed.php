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
        // Update any 'approved' withdrawals to 'completed'
        DB::table('withdraws')
            ->where('status', 'approved')
            ->update(['status' => 'completed']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert back to 'approved' if needed
        DB::table('withdraws')
            ->where('status', 'completed')
            ->update(['status' => 'approved']);
    }
};
