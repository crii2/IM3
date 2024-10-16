<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the config file
require_once 'config.php';

function fetchAirQualityData() {
    $apiKey = "a59291a9-0a65-441f-b642-91425dee1ce0";  // Replace with your actual API key
    $url = "https://api.airvisual.com/v2/city?city=Bern&state=Bern&country=Switzerland&key=" . $apiKey;

    // Initialize a cURL session
    $ch = curl_init($url);

    // Set options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL session and get content
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        return false;
    }

    // Close the cURL session
    curl_close($ch);

    // Decode the JSON response and return data
    return json_decode($response, true);
}

// Fetch data from API
$data = fetchAirQualityData();
if (!$data) {
    die("Failed to fetch data from API");
}

// Debug the response structure
var_dump($data);

// Establish database connection
try {
    $pdo = new PDO($dsn, $username, $password, $options);
    echo "Database connection successful\n";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Extract pollution timestamp and split it into date and time
$pollution_ts = $data['data']['current']['pollution']['ts'] ?? null;
$pollution_date = null;
$pollution_time = null;

if ($pollution_ts) {
    $dateTime = new DateTime($pollution_ts);
    $pollution_date = $dateTime->format('Y-m-d');
    $pollution_time = $dateTime->format('H:i:s');
}

$sql = "INSERT INTO AirQualityWeatherData
    (city, state, country, longitude, latitude, pollution_ts, pollution_date, pollution_time, aqius, mainus, weather_ts, temperature, wind_speed) 
    VALUES (:city, :state, :country, :longitude, :latitude, :pollution_ts, :pollution_date, :pollution_time, :aqius, :mainus, :weather_ts, :temperature, :wind_speed)";

$stmt = $pdo->prepare($sql);

if (isset($data['data']['location']['coordinates'][0]) && isset($data['data']['location']['coordinates'][1])) {
    $longitude = $data['data']['location']['coordinates'][0];
    $latitude = $data['data']['location']['coordinates'][1];

    $stmt->execute([
        ':city' => 'Bern',
        ':state' => 'Bern',
        ':country' => 'Switzerland',
        ':longitude' => $longitude,
        ':latitude' => $latitude,
        ':pollution_ts' => $pollution_ts,
        ':pollution_date' => $pollution_date,
        ':pollution_time' => $pollution_time,
        ':aqius' => $data['data']['current']['pollution']['aqius'] ?? null,
        ':mainus' => $data['data']['current']['pollution']['mainus'] ?? null,
        ':weather_ts' => $data['data']['current']['weather']['ts'] ?? null,
        ':temperature' => $data['data']['current']['weather']['tp'] ?? null,
        ':wind_speed' => $data['data']['current']['weather']['ws'] ?? null
    ]);

    echo "Data successfully inserted into the database for city: Bern.\n";
} else {
    echo "Skipping entry for city: Bern due to missing coordinates.\n";
}
?>
