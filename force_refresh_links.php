<?php
// Load Laravel environment
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Clear any cached data
if (function_exists('artisan')) {
    \Artisan::call('cache:clear');
    \Artisan::call('view:clear');
    \Artisan::call('route:clear');
    \Artisan::call('config:clear');
    echo "All caches cleared!\n";
}

// Add a version timestamp to session to force refresh
$timestamp = time();
\App\Models\Setting::updateOrCreate(
    ['key' => 'referral_links_version'],
    ['value' => $timestamp]
);
echo "Referral links version updated to: " . $timestamp . "\n";

echo "All users will now get fresh referral links on their next visit.\n";
echo "Please try:\n";
echo "1. Log out and log back in\n";
echo "2. Do a hard refresh in your browser (Ctrl+F5)\n";
echo "3. Clear your browser cache\n";
echo "4. If using a mobile app, close and restart it\n"; 