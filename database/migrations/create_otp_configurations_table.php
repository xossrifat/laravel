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
            $table->string('service_name');
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->string('from_number')->nullable();
            $table->string('auth_token')->nullable(); // For CallMeBot
            $table->string('instance_id')->nullable(); // For WhatsApp APIs
            $table->boolean('is_enabled')->default(false);
            $table->json('additional_config')->nullable();
            $table->timestamps();
        });
        
        // Insert default configurations
        DB::table('otp_configurations')->insert([
            [
                'service_name' => 'firebase_sms',
                'is_enabled' => false,
                'additional_config' => json_encode([
                    'project_id' => '',
                    'service_account_json' => ''
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'service_name' => 'callmebot_whatsapp',
                'is_enabled' => false,
                'additional_config' => json_encode([
                    'api_url' => 'https://api.callmebot.com/whatsapp.php'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_configurations');
    }
}; 