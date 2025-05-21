<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\OtpConfiguration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Make sure the otp_configurations table exists
        if (!Schema::hasTable('otp_configurations')) {
            Schema::create('otp_configurations', function (Blueprint $table) {
                $table->id();
                $table->string('service_name')->unique();
                $table->string('api_key')->nullable();
                $table->string('api_secret')->nullable();
                $table->boolean('is_enabled')->default(false);
                $table->text('additional_config')->nullable();
                $table->timestamps();
            });
        }

        // Remove Twilio configuration if it exists
        OtpConfiguration::where('service_name', 'twilio_sms')->delete();
        
        // Check if Firebase configuration already exists
        $firebaseAuth = OtpConfiguration::where('service_name', 'firebase_auth')->first();
        
        if (!$firebaseAuth) {
            // Create a new Firebase Auth config
            $firebaseAuth = new OtpConfiguration();
            $firebaseAuth->service_name = 'firebase_auth';
            $firebaseAuth->api_key = 'YOUR_FIREBASE_WEB_API_KEY';
            $firebaseAuth->is_enabled = true;
            
            // Set default additional config
            $firebaseAuth->additional_config = json_encode([
                'project_id' => 'YOUR_FIREBASE_PROJECT_ID',
                'app_id' => 'YOUR_FIREBASE_APP_ID',
                'auth_domain' => 'YOUR_FIREBASE_PROJECT_ID.firebaseapp.com',
                'messaging_sender_id' => 'YOUR_FIREBASE_SENDER_ID',
            ]);
            
            $firebaseAuth->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove Firebase Auth configuration
        OtpConfiguration::where('service_name', 'firebase_auth')->delete();
    }
}; 