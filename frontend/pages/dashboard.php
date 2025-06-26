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
                    <a href="dashboard.php" class="flex items-center text-white py-2 px-4 rounded hover:bg-brand-purple-light" data-i18n="dashboard">
                        <span data-i18n="dashboard">dashboard</span>
                    </a>
                    <a href="instellingen.php" class="flex items-center text-white py-2 px-4 rounded hover:bg-brand-purple-light">
                        <span data-i18n="instellingen">Instellingen</span>
                    </a>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin'): ?>
                    <a href="admin_management.php" class="flex items-center text-white py-2 px-4 rounded hover:bg-brand-purple-light">
                        <span>Admin Management</span>
                    </a>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['super_admin', 'admin'])): ?>
                    <a href="user_management.php" class="flex items-center text-white py-2 px-4 rounded hover:bg-brand-purple-light<?php if(basename($_SERVER['PHP_SELF']) == 'user_management.php') echo ' bg-brand-purple-light'; ?>">
                        <span>User Management</span>
                    </a>
                    <?php endif; ?>
                    <a href="../../includes/logout.php" class="flex items-center text-white py-2 px-4 rounded hover:bg-brand-purple-light mt-8" data-i18n="logout">
                        <span data-i18n="logout">logout</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-1 flex justify-center">
            <div class="w-full max-w-5xl px-4 py-8">
                <h1 class="text-3xl font-semibold text-gray-800 mb-10 text-center" data-i18n="hoofdtitel">Mijn energieverbruik</h1>
                <form id="filterForm" class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8 bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center gap-2">
                        <label for="dateRange" class="font-medium text-gray-700" data-i18n="periode">Periode:</label>
                        <select id="dateRange" name="dateRange" class="border border-gray-300 rounded px-2 py-1">
                            <option value="vandaag" data-i18n="vandaag">Vandaag</option>
                            <option value="week" data-i18n="week">Deze week</option>
                            <option value="custom" data-i18n="aangepast">Aangepast</option>
                        </select>
                        <input type="date" id="startDate" name="startDate" class="border border-gray-300 rounded px-2 py-1 hidden" />
                        <span id="totLabel" class="text-gray-500 hidden" data-i18n="tot">tot</span>
                        <input type="date" id="endDate" name="endDate" class="border border-gray-300 rounded px-2 py-1 hidden" />
                    </div>
                    <div class="flex items-center gap-2">
                        <label for="filterChartType" class="font-medium text-gray-700" data-i18n="grafiektype">Grafiektype:</label>
                        <select id="filterChartType" name="filterChartType" class="border border-gray-300 rounded px-2 py-1">
                            <option value="lijn" data-i18n="lijn">Lijn</option>
                            <option value="staaf" data-i18n="staaf">Staaf</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-brand-purple hover:bg-brand-purple-light text-white font-bold py-2 px-4 rounded" data-i18n="toepassen">Toepassen</button>
                </form>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="chart-container bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-bold mb-4" data-i18n="spanning">Zonnepaneelspanning (V)</h2>
                        <canvas id="spanningChart"></canvas>
                    </div>
                    <div class="chart-container bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-bold mb-4" data-i18n="stroom">Zonnepaneelstroom (A)</h2>
                        <canvas id="stroomChart"></canvas>
                    </div>
                    <div class="chart-container bg-white rounded-lg shadow p-6 md:col-span-2">
                        <h2 class="text-lg font-bold mb-4" data-i18n="temp">Binnen- en Buitentemperatuur (°C)</h2>
                        <canvas id="tempChart"></canvas>
                    </div>
                    <div class="chart-container bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-bold mb-4" data-i18n="lucht">Luchtvochtigheid (%)</h2>
                        <canvas id="luchtChart"></canvas>
                    </div>
                    <div class="chart-container bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-bold mb-4" data-i18n="waterstof">Waterstofproductie (L/u)</h2>
                        <canvas id="waterstofChart"></canvas>
                    </div>
                    <div class="chart-container bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-bold mb-4" data-i18n="accu">Accuniveau (%)</h2>
                        <canvas id="accuChart"></canvas>
                    </div>
                    <div class="chart-container bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-bold mb-4" data-i18n="co2">CO₂-concentratie binnen (ppm)</h2>
                        <canvas id="co2Chart"></canvas>
                    </div>
                </div>
                <script src="../../js/dashboard.js"></script>
            </div>
        </main>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateRange = document.getElementById('dateRange');
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');
        const totLabel = document.getElementById('totLabel');
        dateRange.addEventListener('change', function() {
            if (this.value === 'custom') {
                startDate.classList.remove('hidden');
                endDate.classList.remove('hidden');
                totLabel.classList.remove('hidden');
            } else {
                startDate.classList.add('hidden');
                endDate.classList.add('hidden');
                totLabel.classList.add('hidden');
            }
        });
    });
    </script>

</body>
</html> 