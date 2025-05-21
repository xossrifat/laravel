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
        Schema::create('otp_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('service_name')->unique();
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->string('from_number')->nullable();
            $table->string('auth_token')->nullable();
            $table->string('instance_id')->nullable();
            $table->boolean('is_enabled')->default(false);
            $table->json('additional_config')->nullable();
            $table->timestamps();
        });
        
        // Add default configurations
        $this->addDefaultConfigurations();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_configurations');
    }
    
    /**
     * Add default OTP configurations
     */
    private function addDefaultConfigurations()
    {
        // Add Firebase SMS config (disabled by default)
        DB::table('otp_configurations')->insert([
            'service_name' => 'firebase_sms',
            'api_key' => null,
            'api_secret' => null,
            'from_number' => null,
            'auth_token' => null,
            'instance_id' => null,
            'is_enabled' => false,
            'additional_config' => json_encode([
                'project_id' => '',
                'region' => 'us-central1',
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Add CallMeBot WhatsApp config (disabled by default)
        DB::table('otp_configurations')->insert([
            'service_name' => 'callmebot_whatsapp',
            'api_key' => null,
            'api_secret' => null,
            'from_number' => null,
            'auth_token' => null,
            'instance_id' => null,
            'is_enabled' => false,
            'additional_config' => json_encode([
                'api_url' => 'https://api.callmebot.com/whatsapp.php',
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
