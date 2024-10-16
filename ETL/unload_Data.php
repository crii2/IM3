<?php

// Include the config file
require_once 'config.php';

// Establish database connection
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get the 'city' parameter from the URL, default to 'Zürich' if not provided
$city = isset($_GET['city']) ? $_GET['city'] : 'Zurich';

// Prepare a SQL query to get unique air quality data for the last 10 timestamps
$sql = "SELECT pollution_date, aqius, temperature, wind_speed
        FROM AirQualityWeatherData
        WHERE city = :city
        AND pollution_date BETWEEN '2024-10-08' AND '2024-10-14'
        GROUP BY pollution_date
        ORDER BY pollution_date ASC";


$stmt = $pdo->prepare($sql);
$stmt->bindParam(':city', $city, PDO::PARAM_STR);
$stmt->execute();

// Fetch the data
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);

?>
