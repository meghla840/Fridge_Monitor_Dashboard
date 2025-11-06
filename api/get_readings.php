<?php
$config = require __DIR__ . '/../config.php';
header('Content-Type: application/json');

$limit = intval($_GET['limit'] ?? 200);

$mysqli = new mysqli(
  $config['db']['host'],
  $config['db']['user'],
  $config['db']['pass'],
  $config['db']['name']
);

if ($mysqli->connect_errno) {
  echo json_encode(['error' => 'DB connect error: ' . $mysqli->connect_error]);
  exit;
}

// Fetch from energy_data instead of fridge_readings
$sql = "SELECT date, energy_kwh FROM energy_data ORDER BY date DESC LIMIT $limit";

$res = $mysqli->query($sql);
$data = [];

if ($res) {
  while ($row = $res->fetch_assoc()) {
    $data[] = [
      'recorded_at' => $row['date'],
      'dp_value' => $row['energy_kwh']
    ];
  }
}

$mysqli->close();
echo json_encode(array_reverse($data)); // oldest first
