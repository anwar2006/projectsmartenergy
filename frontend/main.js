import Chart from 'chart.js/auto';

// Initialize charts and components
document.addEventListener('DOMContentLoaded', () => {
  // Initialize energy consumption chart
  const energyCtx = document.getElementById('energyChart');
  if (energyCtx) {
    new Chart(energyCtx, {
      type: 'line',
      data: {
        labels: [],
        datasets: [{
          label: 'Energy Consumption',
          data: [],
          borderColor: '#1a73e8',
          tension: 0.1
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  }

  // Initialize other components
  initializeNavigation();
  initializeUserProfile();
});

// Navigation handling
function initializeNavigation() {
  const navLinks = document.querySelectorAll('[data-nav-link]');
  navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const page = e.target.dataset.page;
      loadPage(page);
    });
  });
}

// User profile handling
function initializeUserProfile() {
  const profileButton = document.getElementById('profileButton');
  if (profileButton) {
    profileButton.addEventListener('click', () => {
      // Handle profile menu toggle
    });
  }
}

// Page loading
async function loadPage(page) {
  try {
    const response = await fetch(`/api/pages/${page}`);
    const content = await response.text();
    document.getElementById('main-content').innerHTML = content;
  } catch (error) {
    console.error('Error loading page:', error);
  }
} 