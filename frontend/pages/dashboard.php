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
    <link rel="stylesheet" href="../../css/dashboard.css">
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

            <script src="../../js/dashboard.js"></script>
        </main>
    </div>

</body>
</html> 