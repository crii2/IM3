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
                  borderColor: 'rgba(73, 182, 117, 1)',
                  fill: false,
                  borderWidth: 2,
                  pointRadius: 3
              },
              {
                  label: 'Temperature (°C)',
                  data: temperatureValues,
                  borderColor: 'rgba(112, 150, 209, 1)',
                  fill: false,
                  borderWidth: 2,
                  pointRadius: 3
              },
              {
                  label: 'Wind Speed (m/s)',
                  data: windSpeedValues,
                  borderColor: 'rgba(51, 78, 172, 1)',
                  fill: false,
                  borderWidth: 2,
                  pointRadius: 3
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
                      text: 'Date',
                      font: {
                          size: 14
                      }
                  }
              },
              y: {
                  title: {
                      display: true,
                      text: 'Values',
                      font: {
                          size: 14
                      }
                  }
              }
          },
          plugins: {
              legend: {
                  display: true,
                  position: 'top',
                  labels: {
                      font: {
                          size: 14,
                          family: 'Gill Sans'
                      },
                      padding: 30, // Extra padding between legend items
                      usePointStyle: true, // Use point-style markers
                      pointStyle: 'rectRounded', // Use rounded rectangle markers
                      boxWidth: 20, // Box size
                      boxHeight: 10, // Height of the box
                      color: '#444' // Text color
                  }
              },
              tooltip: {
                  backgroundColor: 'rgba(255, 255, 255, 0.9)',
                  borderColor: 'rgba(0, 0, 0, 0.2)',
                  borderWidth: 1,
                  titleColor: '#000',
                  bodyColor: '#333',
                  bodyFont: {
                      family: 'Gill Sans',
                      size: 12
                  },
                  callbacks: {
                      label: function(context) {
                          let label = context.dataset.label || '';
                          if (label) {
                              label += ': ';
                          }
                          label += context.raw;
                          return label;
                      }
                  }
              }
          }
      }
  });
});