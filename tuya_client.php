<?php
// Tuya Client Helper

function get_timestamp() {
    return round(microtime(true) * 1000);
}

function get_signature($client_id, $secret, $t) {
    return strtoupper(hash_hmac('sha256', $client_id . $t, $secret));
}

function get_token($config) {
    $t = get_timestamp();
    $sign = get_signature($config['client_id'], $config['client_secret'], $t);

    $url = rtrim($config['base_url'], '/') . '/v1.0/token?grant_type=1';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{}'); // required for EU API
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "client_id: {$config['client_id']}",
        "sign: $sign",
        "t: $t",
        "sign_method: HMAC-SHA256",
        "Content-Type: application/json"
    ]);

    $res = curl_exec($ch);
    if (curl_errno($ch)) throw new Exception(curl_error($ch));
    curl_close($ch);

    $json = json_decode($res, true);
    if (isset($json['result']['access_token'])) return $json['result']['access_token'];
    throw new Exception('Token error: ' . $res);
}

function get_device_status($config, $token, $device_id) {
    $url = rtrim($config['base_url'], '/') . "/v1.0/devices/$device_id/status";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ]);

    $res = curl_exec($ch);
    if (curl_errno($ch)) throw new Exception(curl_error($ch));
    curl_close($ch);

    return json_decode($res, true);
}
