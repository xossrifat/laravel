<?php
// Load Laravel environment
require_once __DIR__ . '/vendor/autoload.php';

echo "Checking OTP testing mode setting...\n";

// Read the .env file
$envFile = '.env';
$envContent = file_get_contents($envFile);

// Check current setting
if (strpos($envContent, 'OTP_TESTING_MODE=false') !== false) {
    echo "OTP Testing mode is currently DISABLED.\n";
    
    // Replace the setting with true
    $newContent = str_replace('OTP_TESTING_MODE=false', 'OTP_TESTING_MODE=true', $envContent);
    
    // Write back to the file
    file_put_contents($envFile, $newContent);
    
    echo "OTP Testing mode has been ENABLED.\n";
    echo "OTPs will be logged instead of sending real SMS messages.\n";
} else if (strpos($envContent, 'OTP_TESTING_MODE=true') !== false) {
    echo "OTP Testing mode is already ENABLED.\n";
    echo "Your system is already configured to log OTPs instead of sending real SMS messages.\n";
} else {
    // Add the setting if it doesn't exist
    $envContent .= "\nOTP_TESTING_MODE=true\n";
    file_put_contents($envFile, $envContent);
    
    echo "OTP Testing mode has been added and set to ENABLED.\n";
    echo "OTPs will be logged instead of sending real SMS messages.\n";
}

echo "\nImportant Reminders:\n";
echo "1. In testing mode, OTPs will NOT be sent to real phones\n";
echo "2. You can view the OTP codes in the command output when running: php check_otp_config.php test your_mobile_number\n";
echo "3. If you need to disable testing mode to send real SMS messages, run the disable_otp_testing.php script\n"; 