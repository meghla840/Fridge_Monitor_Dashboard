<?php

$conn = new mysqli('localhost', 'root', '', 'fridge_monitor');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$data = [
    ['2025-10-31', 0.61],
    ['2025-11-01', 2.00],
    ['2025-11-02', 1.70],
    ['2025-11-03', 1.55],
    ['2025-11-04', 1.85],
    ['2025-11-05', 1.65],
    ['2025-11-06', 1.74],
];

foreach ($data as $day) {
    $date = $day[0];
    $energy = $day[1];

    $sql = "INSERT INTO energy_data (date, energy_kwh) VALUES ('$date', '$energy')";
    if ($conn->query($sql)) {
        echo "Inserted $date - $energy kWh<br>";
    } else {
        echo " Error inserting $date: " . $conn->error . "<br>";
    }
}

$conn->close();
?>
