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
                    <span class="text-xl font-bold text-brand-purple" data-i18n="brand">Smart Energy Platform</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="flex items-center space-x-2 mr-4">
                        <button id="lang-nl" class="text-2xl" aria-label="Nederlands">🇳🇱</button>
                        <button id="lang-en" class="text-2xl" aria-label="English">🇬🇧</button>
                    </div>
                    <a href="register.php" class="bg-white border border-brand-purple text-brand-purple hover:bg-brand-purple hover:text-white font-bold py-2 px-4 rounded transition-colors inline-flex items-center">
                        <i class="fas fa-user-plus mr-2"></i>
                        <span data-i18n="register">Registreren</span>
                    </a>
                    <a href="login.php" class="bg-brand-purple hover:bg-brand-purple-light text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        <span data-i18n="login">Aanmelden</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="min-h-screen">
        <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between mb-8 md:mb-12">
                <div class="text-center md:text-left md:w-1/2">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 md:mb-6" data-i18n="heroTitle">
                        Monitor and Optimize Your Energy Consumption
                    </h1>
                    <div class="flex justify-center md:justify-start">
                        <a href="login.php" class="bg-brand-purple hover:bg-brand-purple-light text-white font-extrabold py-3 px-8 md:py-4 md:px-10 rounded-xl text-xl md:text-2xl inline-flex items-center shadow-lg transition-all duration-200 mb-4 md:mb-6">
                            Get Started
                            <i class="fas fa-arrow-right ml-4 text-2xl"></i>
                        </a>
                    </div>
                    <p class="text-base md:text-xl text-gray-600 max-w-3xl mx-auto mt-4 md:mt-6" data-i18n="heroText">
                        Krijg direct inzicht in je energieverbruik. Bespaar kosten en optimaliseer je gebruik.
                    </p>
                </div>
                <div class="mt-8 md:mt-0 md:w-1/2 flex justify-center">
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
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-8 mt-8 md:mt-12">
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center transition-transform duration-200 hover:shadow-xl hover:scale-105">
                    <div class="text-brand-purple mb-4">
                        <svg width="40" height="40" fill="none" stroke="#4B2E83" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="4" fill="#fff" stroke="#4B2E83" stroke-width="2.5"/>
                            <polyline points="6,16 10,10 14,14 18,8" stroke="#4B2E83" stroke-width="2.5" fill="none"/>
                            <circle cx="6" cy="16" r="1.5" fill="#4B2E83"/>
                            <circle cx="10" cy="10" r="1.5" fill="#4B2E83"/>
                            <circle cx="14" cy="14" r="1.5" fill="#4B2E83"/>
                            <circle cx="18" cy="8" r="1.5" fill="#4B2E83"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2 text-center whitespace-nowrap" data-i18n="feature1Title">Realtime monitoring</h3>
                    <p class="text-gray-600 text-center" data-i18n="feature1Text">Bekijk je energieverbruik direct met heldere grafieken en analyses.</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center transition-transform duration-200 hover:shadow-xl hover:scale-105">
                    <div class="text-brand-purple mb-4">
                        <svg width="40" height="40" fill="none" stroke="#4B2E83" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2v20M5 12h14"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2 text-center whitespace-nowrap" data-i18n="feature2Title">Slimme optimalisatie</h3>
                    <p class="text-gray-600 text-center" data-i18n="feature2Text">Ontvang persoonlijke tips om je energiegebruik te verbeteren.</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center transition-transform duration-200 hover:shadow-xl hover:scale-105">
                    <div class="text-brand-purple mb-4">
                        <svg width="40" height="40" fill="none" stroke="#4B2E83" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2 text-center whitespace-nowrap" data-i18n="feature3Title">Kosten besparen</h3>
                    <p class="text-gray-600 text-center" data-i18n="feature3Text">Bespaar geld door energieverspilling te voorkomen.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-brand-purple text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 py-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-center md:text-left">
                <div class="mb-2 font-semibold">Contact</div>
                <div>Email: info@smartenergy.com | Tel: 010-1234567</div>
                <div>Adres: Energieweg 1, 1234 AB Rotterdam</div>
            </div>
            <div class="flex flex-col md:flex-row items-center gap-2 md:gap-6">
                <a href="#" class="underline hover:text-gray-200">Privacybeleid</a>
                <a href="#" class="underline hover:text-gray-200">Gebruiksvoorwaarden</a>
            </div>
            <div class="text-center md:text-right mt-2 md:mt-0">
                &copy; 2025 Smart Energy
            </div>
        </div>
    </footer>

    <script>
    const translations = {
      nl: {
        brand: 'Smart Energy Platform',
        register: 'Registreren',
        login: 'Aanmelden',
        heroTitle: 'Monitor en optimaliseer je energieverbruik',
        heroText: 'Krijg direct inzicht in je energieverbruik. Bespaar kosten en optimaliseer je gebruik.',
        feature1Title: 'Realtime monitoring',
        feature1Text: 'Bekijk je energieverbruik direct met heldere grafieken en analyses.',
        feature2Title: 'Slimme optimalisatie',
        feature2Text: 'Ontvang persoonlijke tips om je energiegebruik te verbeteren.',
        feature3Title: 'Kosten besparen',
        feature3Text: 'Bespaar geld door energieverspilling te voorkomen.'
      },
      en: {
        brand: 'Smart Energy Platform',
        register: 'Register',
        login: 'Login',
        heroTitle: 'Monitor and Optimize Your Energy Consumption',
        heroText: 'Get instant insight into your energy usage. Save costs and optimize your consumption.',
        feature1Title: 'Real-time Monitoring',
        feature1Text: 'Track your energy consumption in real-time with detailed analytics and insights.',
        feature2Title: 'Smart Optimization',
        feature2Text: 'Receive personalized suggestions to optimize your energy usage patterns.',
        feature3Title: 'Cost Savings',
        feature3Text: 'Save money by identifying and eliminating energy waste in your system.'
      }
    };
    function setLanguage(lang) {
      const t = translations[lang] || translations['nl'];
      document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        if (t[key]) el.textContent = t[key];
      });
    }
    document.getElementById('lang-nl').onclick = function() { setLanguage('nl'); };
    document.getElementById('lang-en').onclick = function() { setLanguage('en'); };
    // Zet standaard taal op basis van browser of fallback
    setLanguage(navigator.language.startsWith('en') ? 'en' : 'nl');
    </script>
</body>
</html> 