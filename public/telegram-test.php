<?php
/**
 * Telegram API Connection Test Script
 * This script tests different methods of connecting to the Telegram API
 */

// Disable showing errors in production - enable only for testing
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set your bot token
$botToken = '7676225387:AAF_96xtXnB6oFR6Ja2J8LfyLN1TWBi6ajM'; // Replace with your actual bot token

// Test 1: Direct connection
echo "<h2>Test 1: Direct Connection</h2>";
$apiUrl = "https://api.telegram.org/bot{$botToken}/getMe";
echo "Trying to connect to: {$apiUrl}<br>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// Add detailed error reporting
curl_setopt($ch, CURLOPT_VERBOSE, true);
$verbose = fopen('php://temp', 'w+');
curl_setopt($ch, CURLOPT_STDERR, $verbose);

$response = curl_exec($ch);
$error = curl_error($ch);
$info = curl_getinfo($ch);

// Get verbose information
rewind($verbose);
$verboseLog = stream_get_contents($verbose);

if ($error) {
    echo "CURL Error: {$error}<br>";
    echo "Detailed info:<br><pre>{$verboseLog}</pre>";
} else {
    echo "HTTP Status: {$info['http_code']}<br>";
    echo "Response:<br><pre>" . htmlspecialchars($response) . "</pre>";
}

curl_close($ch);

// Test 2: Using proxy
echo "<h2>Test 2: Using Free Public Proxy</h2>";

// Function to get a free public proxy
function getPublicProxy() {
    $proxyListUrl = "https://api.proxyscrape.com/v2/?request=getproxies&protocol=http&timeout=10000&country=all&ssl=all&anonymity=all";
    $proxies = file_get_contents($proxyListUrl);
    $proxyList = explode("\n", trim($proxies));
    return !empty($proxyList) ? $proxyList[0] : null;
}

$proxy = getPublicProxy();
echo "Using proxy: {$proxy}<br>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

if ($proxy) {
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
}

$response = curl_exec($ch);
$error = curl_error($ch);
$info = curl_getinfo($ch);

if ($error) {
    echo "CURL Error: {$error}<br>";
} else {
    echo "HTTP Status: {$info['http_code']}<br>";
    echo "Response:<br><pre>" . htmlspecialchars($response) . "</pre>";
}

curl_close($ch);

// Test 2.5: Using configured proxy
echo "<h2>Test 2.5: Using Your Configured Proxy</h2>";

$customProxy = "http://27.79.137.107:16000";
echo "Using your custom proxy: {$customProxy}<br>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 20); // Longer timeout for proxy connections
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_PROXY, $customProxy);

// Add detailed error reporting
curl_setopt($ch, CURLOPT_VERBOSE, true);
$verbose = fopen('php://temp', 'w+');
curl_setopt($ch, CURLOPT_STDERR, $verbose);

$response = curl_exec($ch);
$error = curl_error($ch);
$info = curl_getinfo($ch);

// Get verbose information
rewind($verbose);
$verboseLog = stream_get_contents($verbose);

if ($error) {
    echo "CURL Error: {$error}<br>";
    echo "Detailed info:<br><pre>{$verboseLog}</pre>";
} else {
    echo "HTTP Status: {$info['http_code']}<br>";
    echo "Response:<br><pre>" . htmlspecialchars($response) . "</pre>";
    
    // Parse the response and show if it worked
    $data = json_decode($response, true);
    if ($data && isset($data['ok']) && $data['ok'] === true) {
        echo "<div style='color: green; font-weight: bold; padding: 10px; border: 1px solid green; margin: 10px 0;'>";
        echo "✓ Success! The proxy connection to Telegram API is working properly!";
        echo "</div>";
    }
}

curl_close($ch);

// Test 3: DNS resolution test
echo "<h2>Test 3: DNS Resolution Test</h2>";

echo "Trying to resolve api.telegram.org:<br>";
$dnsResult = dns_get_record("api.telegram.org", DNS_A);

echo "<pre>" . print_r($dnsResult, true) . "</pre>";

if (empty($dnsResult)) {
    echo "Failed to resolve hostname. This indicates a DNS issue on your hosting.<br>";
    
    // Try to get IP directly
    echo "Trying to get IP address directly:<br>";
    $ip = gethostbyname('api.telegram.org');
    echo "IP for api.telegram.org: {$ip}<br>";
    
    if ($ip != 'api.telegram.org') {
        echo "Direct IP connection test:<br>";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://{$ip}/bot{$botToken}/getMe");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: api.telegram.org"));
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        echo $error ? "Error: {$error}" : "Response: " . htmlspecialchars($response);
        curl_close($ch);
    }
}

// Test 4: Alternative host file approach
echo "<h2>Test 4: System Information</h2>";

echo "PHP Version: " . phpversion() . "<br>";
echo "Operating System: " . PHP_OS . "<br>";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "HTTP User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "<br>";

// Available PHP extensions
echo "<h3>Loaded PHP Extensions</h3>";
$extensions = get_loaded_extensions();
echo "<pre>" . print_r($extensions, true) . "</pre>";

// cURL information
echo "<h3>cURL Information</h3>";
if (function_exists('curl_version')) {
    $curlInfo = curl_version();
    echo "<pre>" . print_r($curlInfo, true) . "</pre>";
} else {
    echo "cURL is not available.";
}

// PHP configuration information related to connections
echo "<h3>PHP Connection Settings</h3>";
$settings = array(
    'allow_url_fopen' => ini_get('allow_url_fopen'),
    'default_socket_timeout' => ini_get('default_socket_timeout'),
    'max_execution_time' => ini_get('max_execution_time'),
    'memory_limit' => ini_get('memory_limit')
);
echo "<pre>" . print_r($settings, true) . "</pre>";

echo "<h2>Recommendations</h2>";
echo "<ul>";
echo "<li>If direct connection failed but DNS resolves properly, try using a proxy in your Laravel application.</li>";
echo "<li>If DNS resolution failed, contact your hosting provider to fix DNS issues or use IP-based connections.</li>";
echo "<li>Consider upgrading to a premium hosting plan that doesn't block external API connections.</li>";
echo "<li>If using InfinityFree, be aware they may restrict outbound connections to some APIs.</li>";
echo "</ul>";

echo "<p>For more help, contact your hosting provider or consider using a premium hosting service with better connectivity options.</p>";

?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram Bot Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        pre {
            background-color: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Telegram Bot Test</h1>
    <p>This page tests the connection to your Telegram bot.</p>
    
    <h2>Curl Response:</h2>
    <?php if ($error): ?>
        <p class="error">Error: <?= htmlspecialchars($error) ?></p>
    <?php else: ?>
        <p class="success">Connection successful!</p>
        <pre><?= htmlspecialchars($response) ?></pre>
        
        <?php 
        $data = json_decode($response, true);
        if ($data && isset($data['ok']) && $data['ok'] === true): 
        ?>
            <h2>Bot Information:</h2>
            <ul>
                <li><strong>Bot ID:</strong> <?= $data['result']['id'] ?></li>
                <li><strong>Bot Name:</strong> <?= $data['result']['first_name'] ?></li>
                <li><strong>Username:</strong> @<?= $data['result']['username'] ?></li>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
    
    <h2>Request Details:</h2>
    <pre><?= htmlspecialchars(print_r($info, true)) ?></pre>
    
    <hr>
    
    <h2>Next Steps:</h2>
    <ul>
        <li>If you see "Connection successful" above, your bot token is valid.</li>
        <li>Configure your Mini App in <a href="https://t.me/BotFather" target="_blank">@BotFather</a>.</li>
        <li>Set your app as the default Mini App: <code>/mybots &gt; [Your Bot] &gt; Bot Settings &gt; Menu Button &gt; Select: Open Mini App</code></li>
        <li>For security, delete this test file once testing is complete.</li>
    </ul>
</body>
</html> 