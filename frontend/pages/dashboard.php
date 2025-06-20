<?php
session_start();
require_once '../../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Smart Energy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-purple': '#4B2E83',
                        'brand-purple-light': '#5d3a9f'
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Top Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-full mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-brand-purple">Smart Energy Dashboard</h1>
                </div>
                <div class="flex items-center">
                    <span class="text-gray-700">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <div class="bg-brand-purple w-64 min-h-screen">
            <div class="p-4">
                <div class="mb-8">
                    <h2 class="text-white text-2xl font-bold mb-1">smart energy</h2>
                </div>
                <nav class="space-y-4">
                    <a href="dashboard.php" class="flex items-center text-white py-2 px-4 rounded hover:bg-brand-purple-light">
                        <span>dashboard</span>
                    </a>
                    <a href="#" class="flex items-center text-white py-2 px-4 rounded hover:bg-brand-purple-light">
                        <span>settings</span>
                    </a>
                    <a href="../../includes/logout.php" class="flex items-center text-white py-2 px-4 rounded hover:bg-brand-purple-light mt-8">
                        <span>logout</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <style>
    body {
      font-family: sans-serif;
      background: #f4f4f4;
    }
    h2 {
      margin-top: 40px;
    }
    .chart-container {
      width: 100%;
      max-width: 900px;
      margin: 0 auto 50px auto;
      background: white;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    canvas {
      max-width: 100%;
      height: 400px;
    }
  </style>
  <div class="chart-container">
    <h2>Zonnepaneelspanning (V)</h2>
    <canvas id="spanningChart"></canvas>
  </div>

  <div class="chart-container">
    <h2>Zonnepaneelstroom (A)</h2>
    <canvas id="stroomChart"></canvas>
  </div>

  <div class="chart-container">
    <h2>Binnen- en Buitentemperatuur (°C)</h2>
    <canvas id="tempChart"></canvas>
  </div>

  <div class="chart-container">
    <h2>Luchtvochtigheid (%)</h2>
    <canvas id="luchtChart"></canvas>
  </div>

  <div class="chart-container">
    <h2>Waterstofproductie (L/u)</h2>
    <canvas id="waterstofChart"></canvas>
  </div>

  <div class="chart-container">
    <h2>Accuniveau (%)</h2>
    <canvas id="accuChart"></canvas>
  </div>

  <div class="chart-container">
    <h2>CO₂-concentratie binnen (ppm)</h2>
    <canvas id="co2Chart"></canvas>
  </div>

  <script>
    fetch('http://localhost:3000')
      .then(res => res.json())
      .then(data => {
        const tijd = data.map(d => d["Tijdstip"]);
        const spanning = data.map(d => d["Zonnepaneelspanning (V)"]);
        const stroom = data.map(d => d["Zonnepaneelstroom (A)"]);
        const buitenTemp = data.map(d => d["Buitentemperatuur (°C)"]);
        const binnenTemp = data.map(d => d["Binnentemperatuur (°C)"]);
        const luchtvochtigheid = data.map(d => d["Luchtvochtigheid (%)"]);
        const waterstof = data.map(d => d["Waterstofproductie (L/u)"]);
        const accu = data.map(d => d["Accuniveau (%)"]);
        const co2 = data.map(d => d["CO2-concentratie binnen (ppm)"]);

        const makeChart = (id, label, data, color) => {
          new Chart(document.getElementById(id), {
            type: 'line',
            data: {
              labels: tijd,
              datasets: [{
                label,
                data,
                borderColor: color,
                backgroundColor: 'rgba(0,0,0,0)',
                borderWidth: 2
              }]
            },
            options: {
              responsive: true,
              plugins: { legend: { display: true } },
              scales: {
                x: { display: true, title: { display: true, text: 'Tijdstip' } },
                y: { beginAtZero: false }
              }
            }
          });
        };

        makeChart('spanningChart', 'Zonnepaneelspanning (V)', spanning, 'orange');
        makeChart('stroomChart', 'Zonnepaneelstroom (A)', stroom, 'green');
        makeChart('tempChart', 'Temperatuur (°C)', [
          {
            label: 'Buitentemperatuur',
            data: buitenTemp,
            borderColor: 'blue'
          },
          {
            label: 'Binnentemperatuur',
            data: binnenTemp,
            borderColor: 'red'
          }
        ], null);
        makeChart('luchtChart', 'Luchtvochtigheid (%)', luchtvochtigheid, 'teal');
        makeChart('waterstofChart', 'Waterstofproductie (L/u)', waterstof, 'purple');
        makeChart('accuChart', 'Accuniveau (%)', accu, 'black');
        makeChart('co2Chart', 'CO₂-concentratie (ppm)', co2, 'gray');
      });
  </script>
        </main>
    </div>

</body>
</html> 