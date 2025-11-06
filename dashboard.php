<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Fridge Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: sans-serif;
      background: #e1e4eaff;
      padding: 20px;
    }
    .card {
      background: white;
      padding: 20px;
      border-radius: 8px;
      max-width: 900px;
      margin: 0 auto;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    label {
      margin-right: 10px;
    }
    h2 {
      color: #070127ff;
    }
    select, input {
      padding: 5px;
      border: 1px solid #d9e6eaff;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <div class="card">
    <h2><i class="fa-solid fa-chart-column"></i> Fridge Dashboard</h2>

    <label>Device: 
      <select id="deviceSelect"></select>
    </label>

    <label>DP Name: 
      <input id="dpName" value="current" />
    </label>

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
          backgroundColor: '#a1b7c4ff', 
          borderColor: '#585079ff',
          borderWidth: 1
        }]
      },
      options: {
        animation: false,
        scales: {
          x: {
            title: { display: true, text: 'Time' },
            ticks: { maxRotation: 90, minRotation: 45 }
          },
          y: {
            beginAtZero: true,
            title: { display: true, text: 'Value' }
          }
        }
      }
    });

   
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
    setInterval(fetchData, 10000); 
  </script>
</body>
</html>
