<?php
$config = require __DIR__ . '/../config.php';
header('Content-Type: application/json');

$device = $_GET['device'] ?? '';
$dp = $_GET['dp'] ?? '';
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

$where = [];
if ($device) $where[] = "device_id='" . $mysqli->real_escape_string($device) . "'";
if ($dp) $where[] = "dp_name='" . $mysqli->real_escape_string($dp) . "'";

$sql = "SELECT device_id, dp_name, dp_value, recorded_at 
        FROM fridge_readings";

if (count($where)) {
  $sql .= " WHERE " . implode(' AND ', $where);
}

$sql .= " ORDER BY recorded_at DESC LIMIT $limit";

$res = $mysqli->query($sql);
$data = [];

if ($res) {
  while ($row = $res->fetch_assoc()) {
    $data[] = $row;
  }
}

$mysqli->close();
echo json_encode(array_reverse($data)); // show oldest first
