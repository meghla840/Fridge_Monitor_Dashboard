<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Smart Fridge Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

 <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="dashboard">
    <h2><i class="fa-solid fa-temperature-high"></i> Smart Fridge Dashboard</h2>

    <div class="input-area">
      <div class="input-group">
        <label><i class="fa-solid fa-microchip"></i> Device</label>
        <select id="deviceSelect"></select>
      </div>
      <div class="input-group">
        <label><i class="fa-solid fa-database"></i> DP Name</label>
        <input id="dpName" value="current" />
      </div>
    </div>

    <canvas id="chart" width="860" height="320"></canvas>
  </div>

<script>
    
  const ctx = document.getElementById('chart').getContext('2d');
    
    const chart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: [],
        datasets: [{
          label: 'Value',
          data: [],
          backgroundColor: 'rgba(180, 210, 224, 0.6)',
          borderColor: '#599fc8ff',
          borderWidth: 1.5,
          borderRadius: 8,
        }]
      },
      options: {
        responsive: true,
        animation: {
          duration: 1200,
          easing: 'easeOutQuart'
        },
        scales: {
          x: {
            title: { display: true, text: 'Time', color: '#333', font: { weight: 'bold' } },
            ticks: { color: '#333' },
            grid: { color: '#f0f0f0' }
          },
          y: {
            beginAtZero: true,
            title: { display: true, text: 'Value', color: '#333', font: { weight: 'bold' } },
            ticks: { color: '#333' },
            grid: { color: '#f0f0f0' }
          }
        },
        plugins: {
          legend: { labels: { color: '#333' } },
          datalabels: {
            anchor: 'end',
            align: 'top',
            color: '#222',
            font: { weight: 'bold', size: 12 },
            formatter: function(value) {
              return value.toFixed(1);
            }
          }
        }
      },
      plugins: [ChartDataLabels]
    });

    // PHP Integration
    const devices = <?php 
      $config = require __DIR__ . '/config.php';
      echo json_encode($config['device_ids']);
    ?>;

    const deviceSelect = document.getElementById('deviceSelect');
    devices.forEach(did => {
      const opt = document.createElement('option');
      opt.value = did;
      opt.textContent = did;
      deviceSelect.appendChild(opt);
    });

    deviceSelect.value = devices[0] || '';

    async function fetchData() {
      const device = deviceSelect.value;
      const dp = document.getElementById('dpName').value || 'current';
      try {
        const res = await fetch(`api/get_readings.php?device=${encodeURIComponent(device)}&dp=${encodeURIComponent(dp)}&limit=200`);
        const arr = await res.json();

        chart.data.labels = arr.map(a => a.recorded_at);
        chart.data.datasets[0].data = arr.map(a => Number(JSON.parse(a.dp_value)) || 0);
        chart.update();
      } catch (err) {
        console.error('Fetch error', err);
      }
    }

    fetchData();

</script>
</body>
</html>
