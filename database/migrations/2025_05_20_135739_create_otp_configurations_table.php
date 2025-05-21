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
            $table->boolean('is_enabled')->default(false);
            $table->text('additional_config')->nullable();
            $table->timestamps();
        });

        // Insert default Twilio SMS configuration
        DB::table('otp_configurations')->insert([
            'service_name' => 'twilio_sms',
            'api_key' => '',  // Account SID will go here
            'api_secret' => '', // Auth Token will go here
            'is_enabled' => false,
            'additional_config' => json_encode(['from_number' => '']),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Insert default CallMeBot WhatsApp configuration
        DB::table('otp_configurations')->insert([
            'service_name' => 'callmebot_whatsapp',
            'api_key' => '',
            'api_secret' => null,
            'is_enabled' => false,
            'additional_config' => json_encode(['api_url' => 'https://api.callmebot.com/whatsapp.php']),
            'created_at' => now(),
            'updated_at' => now()
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
