<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the config file
require_once 'config.php';

function fetchAirQualityData() {
    $apiKey = "a59291a9-0a65-441f-b642-91425dee1ce0";  // Replace with your actual API key
    $url = "https://api.airvisual.com/v2/city?city=Chur&state=Grisons&country=Switzerland&key=" . $apiKey;

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
    // Convert the ISO 8601 string to a DateTime object (handles the Z for UTC)
    $dateTime = new DateTime($pollution_ts);

    // Extract the date in Y-m-d format (e.g., 2024-10-05)
    $pollution_date = $dateTime->format('Y-m-d');

    // Extract the time in H:i:s format (e.g., 16:00:00)
    $pollution_time = $dateTime->format('H:i:s');
}

// Prepare the SQL statement with placeholders for all required fields
$sql = "INSERT INTO AirQualityWeatherData
    (city, state, country, longitude, latitude, pollution_ts, pollution_date, pollution_time, aqius, mainus, weather_ts, temperature, wind_speed) 
    VALUES (:city, :state, :country, :longitude, :latitude, :pollution_ts, :pollution_date, :pollution_time, :aqius, :mainus, :weather_ts, :temperature, :wind_speed)";

$stmt = $pdo->prepare($sql);

// Insert data into the database
if (isset($data['data']['location']['coordinates'][0]) && isset($data['data']['location']['coordinates'][1])) {
    // Extract longitude and latitude from the coordinates array
    $longitude = $data['data']['location']['coordinates'][0];
    $latitude = $data['data']['location']['coordinates'][1];

    // Execute the statement with data from the API response
    $stmt->execute([
        ':city' => $data['data']['city'] ?? null,  // Handle nullable city
        ':state' => $data['data']['state'] ?? null,  // Handle nullable state
        ':country' => $data['data']['country'] ?? null,  // Handle nullable country
        ':longitude' => $longitude,  // Longitude (mandatory)
        ':latitude' => $latitude,  // Latitude (mandatory)
        ':pollution_ts' => $pollution_ts,  // Pollution timestamp
        ':pollution_date' => $pollution_date,  // Extracted pollution date
        ':pollution_time' => $pollution_time,  // Extracted pollution time
        ':aqius' => $data['data']['current']['pollution']['aqius'] ?? null,  // Handle nullable AQI (US)
        ':mainus' => $data['data']['current']['pollution']['mainus'] ?? null,  // Handle nullable main pollutant (US)
        ':weather_ts' => $data['data']['current']['weather']['ts'] ?? null,  // Handle nullable weather timestamp
        ':temperature' => $data['data']['current']['weather']['tp'] ?? null,  // Handle nullable temperature
        ':wind_speed' => $data['data']['current']['weather']['ws'] ?? null  // Handle nullable wind speed
    ]);

    echo "Data successfully inserted into the database for city: " . $data['data']['city'] . ".\n";
} else {
    echo "Skipping entry for city: " . ($data['data']['city'] ?? 'Unknown') . " due to missing coordinates.\n";
}
?>
