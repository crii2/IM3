document.addEventListener('DOMContentLoaded', () => {
    // Replace this with your real API key from IQAir
    const API_KEY = "a59291a9-0a65-441f-b642-91425dee1ce0";
    const CITY = "Zurich"; // For Zurich, use a real city identifier or name
  
    // Fetch air quality data with error handling
    async function getAirQualityData() {
      try {
        const response = await fetch(`https://api.airvisual.com/v2/city?city=${CITY}&state=Zurich&country=Switzerland&key=${API_KEY}`);
        if (!response.ok) {
          throw new Error("Failed to fetch data from API");
        }
        const data = await response.json();
        return data.data.current.pollution;
      } catch (error) {
        console.error(error);
        alert("Error fetching air quality data");
        return null;
      }
    }

    // Chart.js code
    getAirQualityData().then(pollutionData => {
      if (!pollutionData) {
        return; // Handle error case gracefully
      }
  
      const ctx = document.getElementById('aqiChart').getContext('2d');
      const aqiChart = new Chart(ctx, {
        type: 'line',
        data: {
          // Labels are treated as plain text rather than time-based labels
          labels: ['2024-09-01', '2024-09-02', '2024-09-03', '2024-09-04', '2024-09-05'], 
          datasets: [
            {
              label: 'AQI',
              data: [pollutionData.aqius, 30, 45, 60, 50], // Replace with real data
              borderColor: 'rgba(75, 192, 192, 1)',
              fill: false
            },
            {
              label: 'Temperature',
              data: [15, 16, 17, 20, 18],
              borderColor: 'rgba(255, 99, 132, 1)',
              fill: false
            },
            {
              label: 'Wind Speed',
              data: [5, 7, 6, 8, 7],
              borderColor: 'rgba(54, 162, 235, 1)',
              fill: false
            }
          ]
        },
        options: {
          responsive: true, // Ensure responsiveness
          scales: {
            x: {
              // Changed the x-axis to category-based rather than time-based
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
  });