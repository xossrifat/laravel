<?php
// Load Laravel environment
require_once __DIR__ . '/vendor/autoload.php';

echo "Checking OTP testing mode setting...\n";

// Read the .env file
$envFile = '.env';
$envContent = file_get_contents($envFile);

// Check current setting
if (strpos($envContent, 'OTP_TESTING_MODE=true') !== false) {
    echo "OTP Testing mode is currently ENABLED.\n";
    
    // Replace the setting with false
    $newContent = str_replace('OTP_TESTING_MODE=true', 'OTP_TESTING_MODE=false', $envContent);
    
    // Write back to the file
    file_put_contents($envFile, $newContent);
    
    echo "OTP Testing mode has been DISABLED.\n";
    echo "Real SMS messages will now be sent when generating OTPs.\n";
} else if (strpos($envContent, 'OTP_TESTING_MODE=false') !== false) {
    echo "OTP Testing mode is already DISABLED.\n";
    echo "Your system is already configured to send real SMS messages.\n";
} else {
    // Add the setting if it doesn't exist
    $envContent .= "\nOTP_TESTING_MODE=false\n";
    file_put_contents($envFile, $envContent);
    
    echo "OTP Testing mode has been added and set to DISABLED.\n";
    echo "Real SMS messages will now be sent when generating OTPs.\n";
}

echo "\nNext steps:\n";
echo "1. Ensure you have real Twilio credentials configured in the database\n";
echo "2. Try sending a test OTP using: php check_otp_config.php test your_mobile_number\n";
echo "\nIMPORTANT: If you need to enable testing mode again for development, you can run the enable_otp_testing.php script.\n"; 