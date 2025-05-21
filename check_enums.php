<?php
// Simple script to check database schema

// Manually output something basic first
echo "Starting test...\n";

// Load the Laravel framework
require_once __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Framework loaded.\n";

// Use raw DB query to check enum values
try {
    $result = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM notifications WHERE Field = 'type'");
    echo "Query executed.\n";
    
    if (!empty($result)) {
        $typeInfo = $result[0];
        echo "Type column info: " . print_r($typeInfo, true) . "\n";
        
        if (isset($typeInfo->Type)) {
            echo "Enum values: " . $typeInfo->Type . "\n";
            
            if (strpos($typeInfo->Type, "referral") !== false) {
                echo "'referral' is included in the enum!\n";
            } else {
                echo "'referral' is NOT found in the enum.\n";
            }
        }
    } else {
        echo "No type column found!\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "Test completed.\n"; 