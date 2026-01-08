<?php
$cookieFile = __DIR__ . '/.test_cookies.txt';
$url = 'http://127.0.0.1:8000/dashboard';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
$resp = curl_exec($ch);
if ($resp === false) { echo "Error: " . curl_error($ch) . "\n"; exit(1); }
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$hsize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($resp, 0, $hsize);
$body = substr($resp, $hsize);

echo "HTTP: $http\n";
echo "Headers:\n" . $header . "\n";
echo "Body snippet:\n" . substr($body,0,1200) . "\n";
