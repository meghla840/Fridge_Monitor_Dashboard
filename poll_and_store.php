<?php
date_default_timezone_set("UTC");

// ===== 1️⃣ Your Tuya credentials =====
$client_id     = "d8awqg8yg4qf7mucus97";
$client_secret = "f758f69bf0c94c2d90f400701455e0ef";
$device_id     = "bf68b5d8adf4a95c0bloom";
$base_url      = "https://openapi.tuyaeu.com"; // ✅ Correct EU endpoint

// ===== 2️⃣ Get Access Token =====
$t = round(microtime(true) * 1000);
$sign_str = $client_id . $t;
$sign = strtoupper(hash_hmac('sha256', $sign_str, $client_secret));

$url = $base_url . "/v1.0/token?grant_type=1";

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true, // ✅ Must be POST for Tuya
    CURLOPT_POSTFIELDS => '{}',
    CURLOPT_HTTPHEADER => [
        "client_id: $client_id",
        "sign: $sign",
        "t: $t",
        "sign_method: HMAC-SHA256",
        "Content-Type: application/json"
    ],
]);
$res = curl_exec($ch);
curl_close($ch);

$data = json_decode($res, true);

if (empty($data['success']) || !$data['success']) {
    die("❌ Failed to get access token: " . $res);
}

$access_token = $data['result']['access_token'];
echo "✅ Token OK\n";

// ===== 3️⃣ Get Device Status =====
$endpoint = "/v1.0/devices/$device_id/status";
$req_url = $base_url . $endpoint;

$t2 = round(microtime(true) * 1000);
$sign_str2 = $client_id . $access_token . $t2;
$sign2 = strtoupper(hash_hmac('sha256', $sign_str2, $client_secret));

$ch = curl_init($req_url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "client_id: $client_id",
        "sign: $sign2",
        "t: $t2",
        "sign_method: HMAC-SHA256",
        "access_token: $access_token"
    ]
]);
$res2 = curl_exec($ch);
curl_close($ch);

echo "Device response:\n" . $res2 . "\n";
?>
