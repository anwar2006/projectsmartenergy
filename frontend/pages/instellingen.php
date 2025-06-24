<?php
session_start();
require_once '../../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instellingen - Smart Energy</title>
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
                    <span class="text-gray-700">Welkom, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
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
                    <a href="instellingen.php" class="flex items-center text-white py-2 px-4 rounded hover:bg-brand-purple-light bg-brand-purple-light">
                        <span>Instellingen</span>
                    </a>
                    <a href="../../includes/logout.php" class="flex items-center text-white py-2 px-4 rounded hover:bg-brand-purple-light mt-8">
                        <span>logout</span>
                    </a>
                </nav>
            </div>
        </div>
        <!-- Main Content -->
        <main class="flex-1 p-8">
            <h2 class="text-2xl font-bold mb-4 text-brand-purple">Instellingen</h2>
            <p class="text-gray-700 mb-8">Hier kun je je instellingen beheren.</p>
            <form id="instellingenForm" class="bg-white p-6 rounded-lg shadow-md max-w-lg" method="POST" action="">
                <div class="mb-4">
                    <label for="taal" class="block text-gray-700 font-bold mb-2">Taalkeuze</label>
                    <select id="taal" name="taal" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-purple">
                        <option value="nl">Nederlands</option>
                        <option value="en">Engels</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="grafiek" class="block text-gray-700 font-bold mb-2">Voorkeur grafiektype</label>
                    <select id="grafiek" name="grafiek" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-purple">
                        <option value="lijn">Lijn</option>
                        <option value="staaf">Staaf</option>
                        <option value="cirkel">Cirkel</option>
                    </select>
                </div>
                <div class="mb-6 flex items-center">
                    <input id="meldingen" name="meldingen" type="checkbox" class="h-4 w-4 text-brand-purple focus:ring-brand-purple border-gray-300 rounded">
                    <label for="meldingen" class="ml-2 block text-gray-700 font-bold">Meldingen ontvangen</label>
                </div>
                <button type="submit" class="bg-brand-purple hover:bg-brand-purple-light text-white font-bold py-2 px-4 rounded">Opslaan</button>
            </form>
            <div id="melding" class="hidden mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded"></div>
        </main>
    </div>
    <script>
    const translations = {
      nl: {
        instellingen: 'Instellingen',
        beheer: 'Hier kun je je instellingen beheren.',
        taalkeuze: 'Taalkeuze',
        grafiek: 'Voorkeur grafiektype',
        lijn: 'Lijn',
        staaf: 'Staaf',
        cirkel: 'Cirkel',
        meldingen: 'Meldingen ontvangen',
        opslaan: 'Opslaan',
        opgeslagen: 'Instellingen opgeslagen',
        dashboard: 'dashboard',
        logout: 'logout'
      },
      en: {
        instellingen: 'Settings',
        beheer: 'Here you can manage your settings.',
        taalkeuze: 'Language',
        grafiek: 'Preferred chart type',
        lijn: 'Line',
        staaf: 'Bar',
        cirkel: 'Pie',
        meldingen: 'Receive notifications',
        opslaan: 'Save',
        opgeslagen: 'Settings saved',
        dashboard: 'dashboard',
        logout: 'logout'
      }
    };

    function changeLanguage(lang) {
      const t = translations[lang] || translations['nl'];
      document.querySelector('h2.text-2xl').textContent = t.instellingen;
      document.querySelector('p.text-gray-700').textContent = t.beheer;
      document.querySelector('label[for="taal"]').textContent = t.taalkeuze;
      document.querySelector('label[for="grafiek"]').textContent = t.grafiek;
      document.querySelector('option[value="lijn"]').textContent = t.lijn;
      document.querySelector('option[value="staaf"]').textContent = t.staaf;
      document.querySelector('option[value="cirkel"]').textContent = t.cirkel;
      document.querySelector('label[for="meldingen"]').textContent = t.meldingen;
      document.querySelector('button[type="submit"]').textContent = t.opslaan;
      document.querySelector('a[href="dashboard.php"] span').textContent = t.dashboard;
      document.querySelector('a[href="../../includes/logout.php"] span').textContent = t.logout;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('instellingenForm');
        const melding = document.getElementById('melding');
        const taalSelect = document.getElementById('taal');

        // Laad bestaande instellingen uit localStorage
        const opgeslagen = JSON.parse(localStorage.getItem('instellingen'));
        let huidigeTaal = 'nl';
        if (opgeslagen) {
            document.getElementById('taal').value = opgeslagen.taal || 'nl';
            document.getElementById('grafiek').value = opgeslagen.grafiek || 'lijn';
            document.getElementById('meldingen').checked = opgeslagen.meldingen || false;
            huidigeTaal = opgeslagen.taal || 'nl';
        }
        changeLanguage(huidigeTaal);

        taalSelect.addEventListener('change', function() {
            changeLanguage(this.value);
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const taal = document.getElementById('taal').value;
            const grafiek = document.getElementById('grafiek').value;
            const meldingen = document.getElementById('meldingen').checked;
            localStorage.setItem('instellingen', JSON.stringify({ taal, grafiek, meldingen }));
            changeLanguage(taal);
            melding.textContent = translations[taal]?.opgeslagen || 'Instellingen opgeslagen';
            melding.classList.remove('hidden');
            setTimeout(() => melding.classList.add('hidden'), 2500);
        });
    });
    </script>
</body>
</html> 