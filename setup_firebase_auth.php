<?php
/**
 * Setup Firebase Authentication Configuration
 * 
 * This script creates/updates the Firebase configuration in the database
 * for phone number authentication.
 */

// Load Laravel environment
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\OtpConfiguration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "Setting up Firebase Authentication for phone verification...\n";

try {
    // Make sure the otp_configurations table exists
    if (!Schema::hasTable('otp_configurations')) {
        echo "Creating otp_configurations table...\n";
        Schema::create('otp_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('service_name')->unique();
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->boolean('is_enabled')->default(false);
            $table->text('additional_config')->nullable();
            $table->timestamps();
        });
        echo "OTP configurations table created.\n";
    }

    // Check if Firebase configuration already exists
    $firebaseConfig = OtpConfiguration::where('service_name', 'firebase_auth')->first();

    if ($firebaseConfig) {
        echo "Updating existing Firebase authentication configuration...\n";
    } else {
        echo "Creating new Firebase authentication configuration...\n";
        $firebaseConfig = new OtpConfiguration();
        $firebaseConfig->service_name = 'firebase_auth';
    }

    // Set default configuration values
    // You'll need to replace these with your actual Firebase configuration
    $firebaseConfig->api_key = 'YOUR_FIREBASE_API_KEY';
    $firebaseConfig->is_enabled = true;

    // Store Firebase project configuration
    $additionalConfig = [
        'project_id' => 'YOUR_FIREBASE_PROJECT_ID',
        'app_id' => 'YOUR_FIREBASE_APP_ID',
        'auth_domain' => 'YOUR_FIREBASE_PROJECT_ID.firebaseapp.com',
        'messaging_sender_id' => 'YOUR_FIREBASE_SENDER_ID',
        'recaptcha_site_key' => '...',  // Optional: For invisible reCAPTCHA
        'use_emulator' => false,  // Set to true for local development with Firebase emulator
    ];

    $firebaseConfig->additional_config = json_encode($additionalConfig);
    $firebaseConfig->save();

    echo "Firebase authentication configuration saved successfully!\n\n";
    echo "IMPORTANT: You need to update this script with your actual Firebase project details before using it.\n";
    echo "1. Get your Firebase Web API key from the Firebase Console\n";
    echo "2. Update the config values in this file with your actual project details\n";
    echo "3. Run this script again\n\n";

} catch (\Exception $e) {
    echo "Error setting up Firebase authentication: " . $e->getMessage() . "\n";
} 