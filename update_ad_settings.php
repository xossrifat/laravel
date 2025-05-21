<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Define the ad code - use single quotes to avoid escaping issues
$adCode = '<script async="async" data-cfasync="false" src="//pl26671223.profitableratecpm.com/3a179ae28daf2b3a52802eb62ac86e86/invoke.js"></script><div id="container-3a179ae28daf2b3a52802eb62ac86e86"></div>';

// Update settings
\App\Models\Setting::updateOrCreate(
    ['key' => 'spin_ads_enabled'], 
    ['value' => '1']
);

\App\Models\Setting::updateOrCreate(
    ['key' => 'spin_ad_urls'], 
    ['value' => json_encode([$adCode])]
);

// Verify settings were saved correctly
$spinAdsEnabled = \App\Models\Setting::where('key', 'spin_ads_enabled')->first()->value;
$spinAdUrls = json_decode(\App\Models\Setting::where('key', 'spin_ad_urls')->first()->value);

echo "Spin Ads Enabled: " . ($spinAdsEnabled == '1' ? 'Yes' : 'No') . "\n";
echo "Ad Code: " . print_r($spinAdUrls, true) . "\n";

echo "Settings updated successfully\n"; 