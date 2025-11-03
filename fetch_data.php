<?php
include 'config.php';

// Step 1: Get Access Token
$timestamp = round(microtime(true) * 1000);
$stringToSign = $client_id . $timestamp;
$sign = strtoupper(hash_hmac('sha256', $stringToSign, $client_secret));

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "$base_url/v1.0/token?grant_type=1",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "client_id: $client_id",
        "sign: $sign",
        "t: $timestamp",
        "sign_method: HMAC-SHA256"
    ],
]);
$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response, true);
$access_token = $data['result']['access_token'] ?? '';

if (!$access_token) {
    die("❌ Failed to get access token");
}

// Step 2: Fetch Device Data
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "$base_url/v1.0/devices/$device_id/status",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "client_id: $client_id",
        "access_token: $access_token",
        "t: $timestamp",
        "sign_method: HMAC-SHA256"
    ],
]);
$response = curl_exec($curl);
curl_close($curl);

$device_data = json_decode($response, true);

// Step 3: Save data to database
if (!empty($device_data['result'])) {
    foreach ($device_data['result'] as $item) {
        $dp_name = $item['code'];
        $dp_value = $item['value'];
        $conn->query("INSERT INTO fridge_readings (device_id, dp_name, dp_value) VALUES ('$device_id', '$dp_name', '$dp_value')");
    }
}

echo json_encode(["status" => "success"]);
?>
