<?php
/**
 * Run Firebase Authentication Migration
 * 
 * This script will run the Firebase Authentication migration to set up the database
 * and remove Twilio configuration.
 */

// Load Laravel environment
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Running Firebase Authentication migration...\n";

try {
    // Run the migration
    $exitCode = Artisan::call('migrate', [
        '--path' => 'database/migrations/2025_06_01_000000_add_firebase_auth_configuration.php',
        '--force' => true,
    ]);
    
    echo Artisan::output();
    
    if ($exitCode === 0) {
        echo "Firebase Authentication migration completed successfully!\n";
        
        // Check if migration was successful
        $firebaseConfig = DB::table('otp_configurations')
            ->where('service_name', 'firebase_auth')
            ->first();
            
        if ($firebaseConfig) {
            echo "\nFirebase Authentication configuration:\n";
            echo "- API Key: " . $firebaseConfig->api_key . "\n";
            echo "- Enabled: " . ($firebaseConfig->is_enabled ? "Yes" : "No") . "\n";
            
            // Parse additional config
            $additionalConfig = json_decode($firebaseConfig->additional_config, true);
            if (is_array($additionalConfig)) {
                echo "- Project ID: " . ($additionalConfig['project_id'] ?? 'Not set') . "\n";
                echo "- App ID: " . ($additionalConfig['app_id'] ?? 'Not set') . "\n";
            }
            
            echo "\nIMPORTANT: You need to update the Firebase configuration with your actual Firebase project details.\n";
            echo "Run the setup_firebase_auth.php script with your actual Firebase configuration.\n";
        } else {
            echo "\nError: Firebase Authentication configuration not found in database.\n";
        }
    } else {
        echo "Error: Migration failed with exit code $exitCode\n";
    }
} catch (\Exception $e) {
    echo "Error running migration: " . $e->getMessage() . "\n";
} 