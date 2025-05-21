<?php
// Load Laravel environment
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Update the setting
$setting = \App\Models\Setting::updateOrCreate(
    ['key' => 'telegram_bot_username'],
    ['value' => 'SmsSB_bot']
);

echo "Telegram bot username updated to: " . $setting->value . PHP_EOL; 