#  Fridge Energy Monitoring Dashboard

A smart web-based dashboard to **monitor and visualize fridge energy usage** in real-time using data stored in a MySQL database.  
Built with **PHP**, **MySQL**, **Chart.js**, and **Node.js (Tuya API integration)**.



## Overview

This project provides an interactive dashboard that tracks energy consumption of a refrigerator (or any smart appliance) using IoT-based readings. The system retrieves stored data from a MySQL table (`energy_data`) and displays it as a dynamic chart and summary stats.

The setup includes:
- A **Node.js backend** that fetches readings from the Tuya IoT platform.
- A **PHP-based frontend dashboard** to display the energy data visually.
- A **MySQL database** to store and organize historical energy readings.



## Project Structure

Fridge_monitor/
│
├── config.php # Database configuration (MySQL)
├── api/
│ ├── get_readings.php # Fetches data from energy_data table as JSON
│ 
├── dashboard.html # Handles Chart.js visualization
├── insert.php
├── energy_data.sql # Sample SQL schema for the database
└── README.md # Project documentation


## Database Schema

**Table:** `energy_data`

| Column       | Type         | Description                       |
|---------------|--------------|-----------------------------------|
| `id`          | INT (AUTO)   | Primary key                       |
| `date`        | DATETIME     | Timestamp of reading              |
| `energy_kwh`  | FLOAT        | Energy consumption in kilowatt-hour |

**Example SQL:**

CREATE TABLE energy_data (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date DATETIME NOT NULL,
  energy_kwh FLOAT NOT NULL
);
