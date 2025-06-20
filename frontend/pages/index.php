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
                    <a href="register.php" class="bg-white border border-brand-purple text-brand-purple hover:bg-brand-purple hover:text-white font-bold py-2 px-4 rounded transition-colors">
                        Register
                    </a>
                <div class="flex items-center">
                    <a href="login.php" class="bg-brand-purple hover:bg-brand-purple-light text-white font-bold py-2 px-4 rounded">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="min-h-screen">
        <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-6">
                    Monitor and Optimize Your Energy Consumption
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Take control of your energy usage with our smart monitoring platform. Get real-time insights, reduce costs, and contribute to a sustainable future.
                </p>
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

            <!-- Call to Action -->
            <div class="text-center mt-16">
                <a href="login.php" class="bg-brand-purple hover:bg-brand-purple-light text-white font-bold py-3 px-8 rounded-lg text-lg inline-flex items-center">
                    Get Started
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
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
</body>
</html> 