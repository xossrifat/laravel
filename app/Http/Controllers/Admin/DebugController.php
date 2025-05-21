<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Withdraw;
use App\Models\Spin;
use Exception;
use Illuminate\Support\Facades\Schema;

class DebugController extends Controller
{
    /**
     * Debug database connection and tables
     */
    public function debug()
    {
        $output = [];
        
        try {
            // Test database connection
            DB::connection()->getPdo();
            $output['database_connection'] = 'Connected successfully to: ' . DB::connection()->getDatabaseName();
            
            // Check if users table exists and count records
            if (Schema::hasTable('users')) {
                $userCount = DB::table('users')->count();
                $output['users_table'] = "Table exists with {$userCount} records";
                
                // Get list of users for inspection
                $users = DB::table('users')->select('id', 'name', 'email', 'created_at')->get();
                $output['users_list'] = $users;
            } else {
                $output['users_table'] = "Table does not exist";
            }
            
            // Check if withdraws table exists
            if (Schema::hasTable('withdraws')) {
                $withdrawCount = DB::table('withdraws')->count();
                $output['withdraws_table'] = "Table exists with {$withdrawCount} records";
            } else {
                $output['withdraws_table'] = "Table does not exist";
            }
            
            // Check if spins table exists
            if (Schema::hasTable('spins')) {
                $spinCount = DB::table('spins')->count();
                $output['spins_table'] = "Table exists with {$spinCount} records";
            } else {
                $output['spins_table'] = "Table does not exist";
            }
            
            // Get list of all tables in the database
            $tables = DB::select('SHOW TABLES');
            $output['all_tables'] = $tables;
            
        } catch (Exception $e) {
            $output['error'] = $e->getMessage();
            Log::error('Database debug error: ' . $e->getMessage());
        }
        
        return response()->json($output);
    }
} 