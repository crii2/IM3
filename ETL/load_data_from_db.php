<?php

// Include the config file
require_once 'config.php';

// Establish database connection
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Prepare a SQL query to get unique air quality data for the last 5 timestamps
$sql = "SELECT pollution_date, aqius, temperature, wind_speed
        FROM AirQualityWeatherData
        WHERE city = 'Zürich'
        GROUP BY pollution_date
        ORDER BY pollution_date ASC
        LIMIT 5";  // Adjust the limit based on your requirements

$stmt = $pdo->prepare($sql);
$stmt->execute();

// Fetch the data
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);

?>
