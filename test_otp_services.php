<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\OtpConfiguration;
use App\Services\OtpService;

/**
 * Helper function to output in a more readable format
 */
function output($title, $value, $indentLevel = 0) {
    $indent = str_repeat("  ", $indentLevel);
    $title = str_pad($title, 25, " ");
    
    if (is_bool($value)) {
        $value = $value ? "\033[32mTrue\033[0m" : "\033[31mFalse\033[0m";
    } elseif (is_array($value) || is_object($value)) {
        $value = json_encode($value, JSON_PRETTY_PRINT);
    } elseif ($value === null) {
        $value = "\033[33m<null>\033[0m";
    } elseif ($value === '') {
        $value = "\033[33m<empty string>\033[0m";
    }
    
    echo $indent . "\033[1m" . $title . "\033[0m : " . $value . PHP_EOL;
}

echo "===================================\n";
echo "=      OTP SERVICES TEST TOOL     =\n";
echo "===================================\n\n";

// Check OtpConfiguration table
echo "Checking configurations...\n\n";

// List all configurations
$configs = OtpConfiguration::all();
if ($configs->isEmpty()) {
    echo "\033[31mNo OTP configurations found in database.\033[0m\n";
    echo "Please run the migration and add default configurations.\n";
    exit(1);
}

// Check each configuration
foreach ($configs as $config) {
    echo "Service: \033[1m" . $config->service_name . "\033[0m\n";
    output("Enabled", $config->is_enabled, 1);
    output("API Key", $config->api_key, 1);
    output("Additional config", $config->additional_config, 1);
    echo "\n";
}

// Check OTP Service
echo "Testing OTP Service functionality...\n\n";

$otpService = app(OtpService::class);

// Generate OTP
$testOtp = $otpService->generateOtp();
output("Generated test OTP", $testOtp);

// Test options
$options = [
    'test-sms' => null,
    'test-whatsapp' => null,
    'send-sms' => null,
    'send-whatsapp' => null,
    'verify' => null
];

// Check command line arguments
$arg = $argv[1] ?? '';
if (isset($options[$arg])) {
    echo "\nExecuting action: $arg\n";
    
    switch ($arg) {
        case 'test-sms':
            echo "Testing SMS sending placeholder (no actual SMS will be sent)\n";
            $result = $otpService->sendOtp('+1234567890', 'sms');
            output("Result", $result);
            break;
            
        case 'test-whatsapp':
            echo "Testing WhatsApp sending placeholder (no actual message will be sent)\n";
            $result = $otpService->sendOtp('+1234567890', 'whatsapp');
            output("Result", $result);
            break;
            
        case 'send-sms':
            echo "Enter a mobile number to send a real test SMS OTP: ";
            $number = trim(fgets(STDIN));
            if (!$number) {
                echo "Cancelled.\n";
                break;
            }
            echo "Sending SMS to $number...\n";
            $result = $otpService->sendOtp($number, 'sms');
            output("Result", $result);
            break;
            
        case 'send-whatsapp':
            echo "Enter a mobile number to send a real test WhatsApp OTP: ";
            $number = trim(fgets(STDIN));
            if (!$number) {
                echo "Cancelled.\n";
                break;
            }
            echo "Sending WhatsApp to $number...\n";
            $result = $otpService->sendOtp($number, 'whatsapp');
            output("Result", $result);
            break;
            
        case 'verify':
            echo "Enter a mobile number: ";
            $number = trim(fgets(STDIN));
            echo "Enter the OTP code: ";
            $otp = trim(fgets(STDIN));
            if (!$number || !$otp) {
                echo "Cancelled.\n";
                break;
            }
            echo "Verifying OTP...\n";
            $result = $otpService->verifyOtp($number, $otp);
            output("Result", $result);
            break;
    }
    
    exit(0);
}

// Show usage instructions
echo "\nUsage: php test_otp_services.php [action]\n\n";
echo "Available actions:\n";
echo "  test-sms       - Test SMS sending functionality (placeholder)\n";
echo "  test-whatsapp  - Test WhatsApp sending functionality (placeholder)\n";
echo "  send-sms       - Send a real test SMS OTP\n";
echo "  send-whatsapp  - Send a real test WhatsApp OTP\n";
echo "  verify         - Verify an OTP code\n"; 