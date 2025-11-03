<?php
// tuya_hmac_token.php (EU API compatible)
$config = require __DIR__ . '/config.php';

function get_timestamp() {
    return round(microtime(true) * 1000); // milliseconds
}

function get_signature($client_id, $secret, $t) {
    // EU API expects client_id + t HMAC-SHA256 uppercase
    return strtoupper(hash_hmac('sha256', $client_id . $t, $secret));
}

function get_hmac_token($config){
    $client_id = $config['client_id'];
    $secret = $config['client_secret'];
    $t = get_timestamp();
    $sign = get_signature($client_id, $secret, $t);

    $url = rtrim($config['base_url'], '/') . '/v1.0/token?grant_type=1';

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{}'); // required for EU API
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "client_id: $client_id",
        "sign: $sign",
        "t: $t",
        "sign_method: HMAC-SHA256",
        "Content-Type: application/json",
        "mode: cors"
    ]);

    $res = curl_exec($ch);
    if(curl_errno($ch)) { 
        throw new Exception('CURL Error: '.curl_error($ch)); 
    }
    curl_close($ch);

    $json = json_decode($res,true);

    if(isset($json['result']['access_token'])) {
        return $json['result']['access_token'];
    }

    // Show full response for debugging
    throw new Exception('Token error: '.json_encode($json));
}

// Test token fetch
try {
    $token = get_hmac_token($config);
    echo "Token: $token\n";
} catch(Exception $e) {
    echo $e->getMessage();
}
