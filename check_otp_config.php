<?php
// Load Laravel environment
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\OtpConfiguration;
use App\Services\OtpService;
use Illuminate\Support\Facades\Log;

echo "====================================\n";
echo "= OTP CONFIGURATION CHECK UTILITY =\n";
echo "====================================\n\n";

// Check configuration
echo "Checking OTP configurations...\n\n";

// Check Twilio SMS configuration
$twilioConfig = OtpConfiguration::getByService('twilio_sms');
echo "Twilio SMS configuration:\n";
if ($twilioConfig) {
    echo "- Status: " . ($twilioConfig->is_enabled ? "Enabled" : "Disabled") . "\n";
    echo "- Account SID: " . (empty($twilioConfig->api_key) ? "Not set" : substr($twilioConfig->api_key, 0, 10) . "...") . "\n";
    echo "- Auth Token: " . (empty($twilioConfig->api_secret) ? "Not set" : "[Set but hidden for security]") . "\n";
    
    // Parse additional configuration
    $additionalConfig = [];
    if (!empty($twilioConfig->additional_config)) {
        $additionalConfig = is_array($twilioConfig->additional_config) ? 
            $twilioConfig->additional_config : 
            json_decode($twilioConfig->additional_config, true);
    }
    
    $fromNumber = $additionalConfig['from_number'] ?? '';
    echo "- From Phone Number: " . (empty($fromNumber) ? "Not set" : $fromNumber) . "\n";
    
    if (empty($twilioConfig->api_key) || empty($twilioConfig->api_secret) || empty($fromNumber)) {
        echo "  WARNING: Twilio configuration is incomplete. Please set all required fields.\n";
    }
    
    echo "\n";
} else {
    echo "- Not configured. Please set up the Twilio SMS configuration first.\n\n";
}

// Check WhatsApp configuration
$whatsappConfig = OtpConfiguration::getByService('callmebot_whatsapp');
echo "CallMeBot WhatsApp configuration:\n";
if ($whatsappConfig) {
    echo "- Status: " . ($whatsappConfig->is_enabled ? "Enabled" : "Disabled") . "\n";
    echo "- API Key: " . (empty($whatsappConfig->api_key) ? "Not set" : substr($whatsappConfig->api_key, 0, 10) . "...") . "\n";
    
    // Parse additional configuration
    $additionalConfig = [];
    if (!empty($whatsappConfig->additional_config)) {
        $additionalConfig = is_array($whatsappConfig->additional_config) ? 
            $whatsappConfig->additional_config : 
            json_decode($whatsappConfig->additional_config, true);
    }
    
    $apiUrl = $additionalConfig['api_url'] ?? 'https://api.callmebot.com/whatsapp.php';
    echo "- API URL: " . $apiUrl . "\n";
    
    echo "\n";
} else {
    echo "- Not configured. Please set up the CallMeBot WhatsApp configuration first.\n\n";
}

// Check testing mode
echo "Testing mode: " . (env('OTP_TESTING_MODE', false) ? "Enabled" : "Disabled") . "\n\n";

// Check if testing is requested
if ($argc > 1) {
    // Get command arguments
    $command = $argc > 1 ? $argv[1] : "";
    $mobileNumber = $argc > 2 ? $argv[2] : null;
    
    // If command is test without a mobile number, explain usage
    if ($command === "test" && !$mobileNumber) {
        echo "To test OTP sending, use: php check_otp_config.php test [mobile_number]\n";
        echo "Example: php check_otp_config.php test 1712345678\n\n";
        exit;
    }
    
    // Handle test command
    if ($command === "test" && $mobileNumber) {
        echo "Testing OTP sending to: $mobileNumber\n";
        
        // Format mobile number
        if (!str_starts_with($mobileNumber, '+')) {
            $mobileNumber = '+880' . ltrim($mobileNumber, '0');
        }
        
        // Create OTP service
        $otpService = new OtpService();
        
        // Send OTP
        $result = $otpService->sendOtp($mobileNumber);
        
        echo "Result: " . ($result['success'] ? "Success" : "Failed") . "\n";
        echo "Message: " . $result['message'] . "\n";
        
        if ($result['success'] && env('OTP_TESTING_MODE', false)) {
            $otp = \Illuminate\Support\Facades\Cache::get('otp_code_' . $mobileNumber);
            echo "Testing mode OTP: $otp\n";
        }
        
        echo "\n";
    }
} else {
    // Show usage
    echo "Usage: php check_otp_config.php [command] [parameters]\n";
    echo "Commands:\n";
    echo "  test [mobile_number] - Test sending OTP to the specified mobile number\n";
    echo "Example: php check_otp_config.php test 1712345678\n\n";
}

echo "Configuration check completed.\n"; 