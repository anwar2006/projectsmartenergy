<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Energy Platform - Monitor and Optimize Your Energy Usage</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-xl font-bold text-brand-purple">Smart Energy Platform</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="flex items-center space-x-2 mr-4">
                        <button id="lang-nl" class="text-2xl" aria-label="Nederlands">🇳🇱</button>
                        <button id="lang-en" class="text-2xl" aria-label="English">🇬🇧</button>
                    </div>
                    <a href="register.php" class="bg-white border border-brand-purple text-brand-purple hover:bg-brand-purple hover:text-white font-bold py-2 px-4 rounded transition-colors inline-flex items-center">
                        <i class="fas fa-user-plus mr-2"></i>
                        Registreren
                    </a>
                    <a href="login.php" class="bg-brand-purple hover:bg-brand-purple-light text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Aanmelden
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="min-h-screen">
        <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between mb-12">
                <div class="text-center md:text-left md:w-1/2">
                    <h1 class="text-4xl font-bold text-gray-900 mb-6">
                        Monitor and Optimize Your Energy Consumption
                    </h1>
                    <a href="login.php" class="bg-brand-purple hover:bg-brand-purple-light text-white font-extrabold py-4 px-10 rounded-xl text-2xl inline-flex items-center shadow-lg transition-all duration-200 mb-6">
                        Get Started
                        <i class="fas fa-arrow-right ml-4 text-2xl"></i>
                    </a>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto mt-6">
                        Krijg direct inzicht in je energieverbruik. Bespaar kosten en optimaliseer je gebruik.
                    </p>
                </div>
                <div class="mt-10 md:mt-0 md:w-1/2 flex justify-center">
                    <!-- Hero SVG Illustration -->
                    <svg width="320" height="220" viewBox="0 0 320 220" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="30" y="120" width="80" height="50" rx="8" fill="#4B2E83"/>
                        <rect x="120" y="100" width="80" height="70" rx="8" fill="#5d3a9f"/>
                        <rect x="210" y="80" width="80" height="90" rx="8" fill="#4B2E83"/>
                        <rect x="60" y="140" width="20" height="10" rx="2" fill="#fff"/>
                        <rect x="150" y="120" width="20" height="10" rx="2" fill="#fff"/>
                        <rect x="240" y="100" width="20" height="10" rx="2" fill="#fff"/>
                        <polyline points="40,170 80,150 160,130 250,110 280,100" stroke="#FFD600" stroke-width="4" fill="none" stroke-linecap="round"/>
                        <circle cx="280" cy="100" r="10" fill="#FFD600"/>
                        <rect x="60" y="180" width="200" height="8" rx="4" fill="#E5E7EB"/>
                        <rect x="90" y="188" width="140" height="8" rx="4" fill="#E5E7EB"/>
                        <text x="40" y="210" fill="#4B2E83" font-size="16" font-family="Arial">Solar Panels</text>
                        <text x="200" y="210" fill="#4B2E83" font-size="16" font-family="Arial">Energy Flow</text>
                    </svg>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-brand-purple text-3xl mb-4">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Real-time Monitoring</h3>
                    <p class="text-gray-600">
                        Track your energy consumption in real-time with detailed analytics and insights.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-brand-purple text-3xl mb-4">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Smart Optimization</h3>
                    <p class="text-gray-600">
                        Receive personalized suggestions to optimize your energy usage patterns.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-brand-purple text-3xl mb-4">
                        <i class="fas fa-euro-sign"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Cost Savings</h3>
                    <p class="text-gray-600">
                        Save money by identifying and eliminating energy waste in your system.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-brand-purple text-white">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="text-center">
                <p>&copy; <?php echo date('Y'); ?> Smart Energy Platform. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
    // Eenvoudige taalkeuze (alleen visueel, geen i18n functionaliteit)
    document.getElementById('lang-nl').onclick = function() { alert('Nederlands geselecteerd (i18n kan hier worden toegevoegd)'); };
    document.getElementById('lang-en').onclick = function() { alert('English selected (i18n can be added here)'); };
    </script>
</body>
</html> 