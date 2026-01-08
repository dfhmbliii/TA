<?php
// Quick test script: fetch login page, extract CSRF token, POST credentials and print response
$base = 'http://127.0.0.1:8000';
$loginUrl = $base . '/login';
$cookieFile = __DIR__ . '/.test_cookies.txt';

// GET login page
$ch = curl_init($loginUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
$resp = curl_exec($ch);
if ($resp === false) { echo "GET error: " . curl_error($ch) . "\n"; exit(1); }
$hsize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$body = substr($resp, $hsize);
curl_close($ch);

// extract CSRF token
if (!preg_match('/name="_token" value="([^"]+)"/', $body, $m)) {
    echo "Unable to find CSRF token in login page.\n";
    echo "Login page snippet:\n" . substr($body,0,800) . "\n";
    exit(1);
}
$token = $m[1];
echo "Found CSRF token: " . substr($token,0,20) . "...\n";

// prepare POST
$post = http_build_query([
    '_token' => $token,
    'email' => 'admin@admin.com',
    'password' => 'password123'
]);

$ch = curl_init($loginUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_HEADER, true);
// don't follow redirects so we can inspect the Location header
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
$resp = curl_exec($ch);
if ($resp === false) { echo "POST error: " . curl_error($ch) . "\n"; exit(1); }
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$hsize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($resp, 0, $hsize);
$body = substr($resp, $hsize);

echo "POST HTTP status: $http\n";
echo "Response headers:\n" . $header . "\n";
echo "Body snippet:\n" . substr($body,0,800) . "\n";

// cleanup cookie file (optional)
// @unlink($cookieFile);
