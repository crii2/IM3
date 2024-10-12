<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the config file
require_once 'config.php';

function fetchAirQualityData($city, $state, $country, $apiKey) {
    $apiKey = "a59291a9-0a65-441f-b642-91425dee1ce0";  // Replace with your actual API key
    $url = "https://api.airvisual.com/v2/city?city=" . urlencode($city) . "&state=" . urlencode($state) . "&country=" . urlencode($country) . "&key=" . $apiKey;

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

// Define your API key
$apiKey = "a59291a9-0a65-441f-b642-91425dee1ce0";  // Replace with your actual API key

// Define the cities, states, and countries
$cities = [
    ['city' => 'Zurich', 'state' => 'Zurich', 'country' => 'Switzerland'],
    ['city' => 'Lugano', 'state' => 'Ticino', 'country' => 'Switzerland'],
    ['city' => 'Chur', 'state' => 'Grisons', 'country' => 'Switzerland'],
    ['city' => 'Jungfraujoch', 'state' => 'Valais', 'country' => 'Switzerland'],
    ['city' => 'Davos', 'state' => 'Grisons', 'country' => 'Switzerland'],
    ['city' => 'Martigny-Combe', 'state' => 'Valais', 'country' => 'Switzerland'],
    ['city' => 'Bern', 'state' => 'Bern', 'country' => 'Switzerland']
];

// Establish database connection
try {
    $pdo = new PDO($dsn, $username, $password, $options);
    echo "Database connection successful\n";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Prepare the SQL statement with placeholders for all required fields
$sql = "INSERT INTO AirQualityWeatherData
    (city, state, country, longitude, latitude, pollution_ts, aqius, mainus, weather_ts, temperature, wind_speed) 
    VALUES (:city, :state, :country, :longitude, :latitude, :pollution_ts, :aqius, :mainus, :weather_ts, :temperature, :wind_speed)";
$stmt = $pdo->prepare($sql);

// Loop through each city and fetch data
foreach ($cities as $location) {
    $data = fetchAirQualityData($location['city'], $location['state'], $location['country'], $apiKey);

    if (!$data) {
        echo "Failed to fetch data for city: " . $location['city'] . "\n";
        continue;
    }

    // Debug the response structure if needed
    // var_dump($data);

    if (isset($data['data']['location']['coordinates'][0]) && isset($data['data']['location']['coordinates'][1])) {
        // Extract longitude and latitude from the coordinates array
        $longitude = $data['data']['location']['coordinates'][0];
        $latitude = $data['data']['location']['coordinates'][1];

        // Execute the statement with data from the API response
        $stmt->execute([
            ':city' => $data['data']['city'] ?? null,
            ':state' => $data['data']['state'] ?? null,
            ':country' => $data['data']['country'] ?? null,
            ':longitude' => $longitude,
            ':latitude' => $latitude,
            ':pollution_ts' => $data['data']['current']['pollution']['ts'] ?? null,
            ':aqius' => $data['data']['current']['pollution']['aqius'] ?? null,
            ':mainus' => $data['data']['current']['pollution']['mainus'] ?? null,
            ':weather_ts' => $data['data']['current']['weather']['ts'] ?? null,
            ':temperature' => $data['data']['current']['weather']['tp'] ?? null,
            ':wind_speed' => $data['data']['current']['weather']['ws'] ?? null
        ]);

        echo "Data successfully inserted into the database for city: " . $data['data']['city'] . ".\n";
    } else {
        echo "Skipping entry for city: " . ($data['data']['city'] ?? 'Unknown') . " due to missing coordinates.\n";
    }
}
?>
