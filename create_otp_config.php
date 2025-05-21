<?php
// Load Laravel environment
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// Create otp_configurations table if it doesn't exist
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
    
    echo "Created otp_configurations table\n";
} else {
    echo "Table otp_configurations already exists\n";
}

// Insert or update Twilio SMS configuration
DB::table('otp_configurations')->updateOrInsert(
    ['service_name' => 'twilio_sms'],
    [
        'api_key' => 'YOUR_TWILIO_ACCOUNT_SID',
        'api_secret' => 'YOUR_TWILIO_AUTH_TOKEN',
        'is_enabled' => true,
        'additional_config' => json_encode(['from_number' => '+15551234567']),
        'created_at' => now(),
        'updated_at' => now()
    ]
);

echo "Added Twilio SMS configuration\n";

// Check if the record was added
$twilioConfig = DB::table('otp_configurations')->where('service_name', 'twilio_sms')->first();
if ($twilioConfig) {
    echo "Verified: Twilio SMS configuration exists in the database\n";
    var_export($twilioConfig);
    echo "\n";
} else {
    echo "Error: Could not find Twilio SMS configuration in database\n";
}

echo "Done.\n"; 