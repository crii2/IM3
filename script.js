const API_KEY = "a59291a9-0a65-441f-b642-91425dee1ce0";

// City data for API usage
const cities = {
  zurich: { name: "Zurich", state: "Zurich", country: "Switzerland" },
  bern: { name: "Bern", state: "Bern", country: "Switzerland" },
  jungfraujoch: { name: "Jungfraujoch", state: "Valais", country: "Switzerland" },
  martignycombe: { name: "Martigny-Combe", state: "Valais", country: "Switzerland" },
  chur: { name: "Chur", state: "Graubünden", country: "Switzerland" },
  davos: { name: "Davos", state: "Graubünden", country: "Switzerland" },
  lugano: { name: "Lugano", state: "Ticino", country: "Switzerland" }
};

// Fetch air quality data for a city
async function getAirQualityData(cityKey) {
  const city = cities[cityKey];
  const response = await fetch(`https://api.airvisual.com/v2/city?city=${city.name}&state=${city.state}&country=${city.country}&key=${API_KEY}`);
  const data = await response.json();
  return data.data.current.pollution;
}

// Show city details overlay
function showCityDetails(cityKey) {
  const cityDetails = document.getElementById('city-details');
  const cityContent = document.getElementById('city-content');
  
  // Fetch and display the air quality data
  getAirQualityData(cityKey).then(pollutionData => {
    const aqi = pollutionData.aqius; // Air Quality Index (US)
    cityContent.innerHTML = `
      <h2>Air Quality in ${cities[cityKey].name}</h2>
      <p>AQI (Air Quality Index): ${aqi}</p>
      <canvas id="aqiChart"></canvas>
    `;
    
    // Initialize chart after showing data
    /*const ctx = document.getElementById('aqiChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['2024-09-01', '2024-09-02', '2024-09-03', '2024-09-04', '2024-09-05'], // Replace with actual time data
        datasets: [{
          label: 'AQI',
          data: [aqi, aqi - 10, aqi - 5, aqi + 5, aqi - 15], // Replace with real historical data
          borderColor: 'rgba(75, 192, 192, 1)',
          fill: false
        }]
      }
    });*/
  });

  cityDetails.classList.remove('hidden');
}

// Close city details overlay
document.querySelector('.close-btn').addEventListener('click', () => {
  document.getElementById('city-details').classList.add('hidden');
});

// Set up event listeners for city dots
document.querySelectorAll('.city-dot').forEach(dot => {
  dot.addEventListener('click', function () {
    const cityKey = this.getAttribute('data-city');
    showCityDetails(cityKey);
  });
});
