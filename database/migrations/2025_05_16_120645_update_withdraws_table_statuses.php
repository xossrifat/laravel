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
        // MySQL-specific approach
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE withdraws MODIFY COLUMN status ENUM('pending', 'completed', 'rejected', 'approved') DEFAULT 'pending'");
        } 
        // For other database systems, a more complex approach would be needed
        else {
            // For PostgreSQL, SQLite, etc., you'd need to create a new column, 
            // copy data, drop the old column, and rename the new one
            Schema::table('withdraws', function (Blueprint $table) {
                // Create a temporary column without enum constraint
                $table->string('status_new')->default('pending');
            });
            
            // Copy data from old column to new
            DB::table('withdraws')->update([
                'status_new' => DB::raw('status')
            ]);
            
            // Drop old column and rename new one
            Schema::table('withdraws', function (Blueprint $table) {
                $table->dropColumn('status');
            });
            
        Schema::table('withdraws', function (Blueprint $table) {
                $table->renameColumn('status_new', 'status');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // MySQL-specific approach
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE withdraws MODIFY COLUMN status ENUM('pending', 'completed', 'rejected') DEFAULT 'pending'");
        } 
        // For other database systems
        else {
            Schema::table('withdraws', function (Blueprint $table) {
                $table->string('status_new')->default('pending');
            });
            
            // Convert 'approved' to 'completed' in the rollback
            DB::table('withdraws')
                ->where('status', 'approved')
                ->update(['status_new' => 'completed']);
                
            // Copy other statuses as they are
            DB::table('withdraws')
                ->whereIn('status', ['pending', 'completed', 'rejected'])
                ->update([
                    'status_new' => DB::raw('status')
                ]);
            
            Schema::table('withdraws', function (Blueprint $table) {
                $table->dropColumn('status');
            });
            
        Schema::table('withdraws', function (Blueprint $table) {
                $table->renameColumn('status_new', 'status');
        });
        }
    }
};
