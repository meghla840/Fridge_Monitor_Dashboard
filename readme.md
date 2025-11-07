# Fridge Energy Monitoring Dashboard

A smart web-based dashboard to monitor and visualize fridge energy usage in real-time using data stored in a MySQL database.

Built with PHP, MySQL, and Chart.js.

**Overview**

This project provides an interactive dashboard that tracks energy consumption of a refrigerator (or any smart appliance) using stored readings. The system retrieves historical energy data from a MySQL table (energy_data) and displays it as dynamic charts and summary statistics.

**Features:**

Real-time visualization of energy consumption using Chart.js.

Historical data storage and retrieval via MySQL.

Simple and responsive dashboard built with PHP.

**Project Structure**
Fridge_monitor/
- config.php           # Database configuration (MySQL)
- api/
  - get_readings.php # Fetches data from energy_data table as JSON
- insert.php       # Inserts new energy readings into the database
- dashboard.php        # Dashboard page with Chart.js visualization
- style.css            # Custom styling for dashboard
- energy_data.sql      # Sample SQL schema for the database
- README.md            # Project documentation

**Database Schema**

Table: energy_data

Column|	Type |	Description
id	| INT AUTO |	Primary key
date |	DATETIME |	Timestamp of reading
energy_kwh |	FLOAT	| Energy consumption in kWh

**Example SQL:**

CREATE TABLE energy_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATETIME NOT NULL,
    energy_kwh FLOAT NOT NULL
);

Setup Instructions

**Clone the project:**

##### git clone `https://github.com/meghla840/Fridge_Monitor_Dashboard.git`


**Import the database:**

Open phpMyAdmin or MySQL CLI.

Create a database (e.g., fridge_monitor).

Import `energy_data.sql` to create the table.

**Configure database connection:**

Open `config.php`.

Update your MySQL credentials:

<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "fridge_monitor";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


**Insert sample readings (optional):**

Use `insert.php` or manually add entries to the energy_data table.

**Run the dashboard:**

Open dashboard.php in your browser.

You should see interactive charts displaying energy consumption over time.

**How it Works**

- **Data Storage:**
Energy readings are stored in the energy_data table with a timestamp and consumption value in kWh.

- **Data Fetching:**
get_readings.php retrieves the readings as JSON.

- **Visualization:**
dashboard.php uses Chart.js to display a line chart of energy usage, updating dynamically based on database entries.

- **Technologies Used**

    - PHP – Backend scripting for fetching and inserting data.

    - MySQL – Stores energy readings and historical data.

    - Chart.js – Displays interactive charts on the dashboard.

    - HTML / CSS – Dashboard UI design.

- **Future Enhancements**

    - Add real-time data updates using AJAX.

    - Include daily/weekly/monthly analytics.

    - Support multiple appliances in a single dashboard.

    - Integrate with IoT devices for automated readings.

- **Screenshot:**

![dashboard screenshort](image.png)