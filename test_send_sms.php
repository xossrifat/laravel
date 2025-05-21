<?php
// Load Laravel environment
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Turn off testing mode temporarily
putenv('OTP_TESTING_MODE=false');

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\OtpConfiguration;

echo "====================================\n";
echo "=    DIRECT SMS SENDING TEST TOOL  =\n";
echo "====================================\n\n";

// Parse command line arguments
$phoneNumber = $argv[1] ?? null;

if (!$phoneNumber) {
    echo "Please provide a phone number as a parameter.\n";
    echo "Usage: php test_send_sms.php +8801712345678\n";
    exit(1);
}

echo "Testing SMS sending to: $phoneNumber\n\n";

// Format phone number
if (!str_starts_with($phoneNumber, '+')) {
    $phoneNumber = '+' . $phoneNumber;
}

// 1. Send SMS using Twilio
echo "1. Testing Twilio SMS...\n";

$twilioConfig = OtpConfiguration::getByService('twilio_sms');
if (!$twilioConfig || !$twilioConfig->is_enabled) {
    echo "Twilio SMS is not enabled or configured!\n";
} else {
    $accountSid = $twilioConfig->api_key;
    $authToken = $twilioConfig->api_secret;
    $fromNumber = '';
    
    // Get the "from" phone number from additional config
    if (!empty($twilioConfig->additional_config)) {
        $additionalConfig = json_decode($twilioConfig->additional_config, true);
        $fromNumber = $additionalConfig['from_number'] ?? '';
    }
    
    if (empty($accountSid) || empty($authToken) || empty($fromNumber)) {
        echo "Twilio configuration is incomplete!\n";
    } else {
        echo "Sending SMS using Twilio API...\n";
        
        // Generate a random OTP
        $otp = rand(100000, 999999);
        $message = "Your verification code is: $otp";
        
        try {
            // Make API request to Twilio
            $twilioApiUrl = "https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json";
            
            $response = Http::withBasicAuth($accountSid, $authToken)
                ->asForm()
                ->post($twilioApiUrl, [
                    'From' => $fromNumber,
                    'To' => $phoneNumber,
                    'Body' => $message
                ]);
            
            echo "Twilio API Response: " . substr($response->body(), 0, 300) . "\n";
            echo "Status: " . ($response->successful() ? "Success" : "Failed") . "\n";
            
            if ($response->successful()) {
                $responseData = $response->json();
                echo "Message SID: " . ($responseData['sid'] ?? 'Unknown') . "\n";
                echo "Status: " . ($responseData['status'] ?? 'Unknown') . "\n";
            } else {
                $errorData = $response->json();
                echo "Error Code: " . ($errorData['code'] ?? 'Unknown') . "\n";
                echo "Error Message: " . ($errorData['message'] ?? 'Unknown error') . "\n";
            }
        } catch (\Exception $e) {
            echo "Error sending SMS with Twilio: " . $e->getMessage() . "\n";
        }
    }
}

echo "\n2. Testing WhatsApp OTP via CallMeBot...\n";

$whatsappConfig = OtpConfiguration::getByService('callmebot_whatsapp');
if (!$whatsappConfig || !$whatsappConfig->is_enabled) {
    echo "CallMeBot WhatsApp is not enabled or configured!\n";
} else {
    $apiKey = $whatsappConfig->api_key;
    if (empty($apiKey)) {
        echo "CallMeBot API key is missing!\n";
    } else {
        echo "Sending WhatsApp message using CallMeBot...\n";
        
        // Generate a random OTP
        $otp = rand(100000, 999999);
        $message = "Your verification code is: $otp";
        
        try {
            // Format phone number (remove + and spaces)
            $formattedNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
            
            // Get API URL
            $apiUrl = $whatsappConfig->additional_config['api_url'] ?? 'https://api.callmebot.com/whatsapp.php';
            
            // URL encode the message
            $encodedMessage = urlencode($message);
            
            echo "Calling CallMeBot API at: $apiUrl\n";
            echo "Phone: $formattedNumber\n";
            
            // Make API request to CallMeBot
            $response = Http::timeout(30)->get($apiUrl, [
                'phone' => $formattedNumber,
                'apikey' => $apiKey,
                'text' => $encodedMessage
            ]);
            
            echo "CallMeBot API Response: " . $response->body() . "\n";
            echo "Status: " . ($response->successful() ? "Success" : "Failed") . "\n";
            
        } catch (\Exception $e) {
            echo "Error sending WhatsApp with CallMeBot: " . $e->getMessage() . "\n";
        }
    }
}

echo "\nTest completed.\n";
echo "For help setting up an SMS provider:\n";
echo "1. Twilio: https://www.twilio.com/docs/sms/quickstart/php\n";
echo "2. Nexmo/Vonage: https://developer.vonage.com/en/messaging/sms/overview\n";
echo "3. CallMeBot WhatsApp: https://www.callmebot.com/blog/free-api-whatsapp-messages/\n";
echo "\nNote: To enable WhatsApp messaging with CallMeBot, you need to set up your phone with their service first.\n"; 