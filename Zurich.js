async function getAirQualityData() {
    try {
      const response = await fetch('ETL/load_data_from_db.php'); // Fetch data from the PHP script
      const data = await response.json();
      console.log("Data loaded successfull");
      return data;
    } catch (error) {
      console.error('Error fetching air quality data:', error);
      return null;
    }
  }
  
  getAirQualityData().then(pollutionData => {
    if (!pollutionData) {
      return; // Handle error case gracefully
    }
  
    const dates = pollutionData.map(entry => new Date(entry.pollution_date).toISOString().split('T')[0]);
    const aqiValues = pollutionData.map(entry => entry.aqius);
    const temperatureValues = pollutionData.map(entry => entry.temperature);
    const windSpeedValues = pollutionData.map(entry => entry.wind_speed);
  
    const ctx = document.getElementById('aqiChart').getContext('2d');
    const aqiChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: dates,
        datasets: [
          {
            label: 'AQI (Air Quality Index)',
            data: aqiValues,
            borderColor: 'rgba(73, 182, 117)',
            fill: false
          },
          {
            label: 'Temperature',
            data: temperatureValues,
            borderColor: 'rgba(255, 99, 132)',
            fill: false
          },
          {
            label: 'Wind Speed',
            data: windSpeedValues,
            borderColor: 'rgba(144, 213, 255)',
            fill: false
          }
        ]
      },
      options: {
        responsive: true,
        scales: {
          x: {
            type: 'category',
            title: {
              display: true,
              text: 'Date'
            }
          },
          y: {
            title: {
              display: true,
              text: 'Values'
            }
          }
        }
      }
    });
  });