const instellingen = JSON.parse(localStorage.getItem('instellingen')) || {};
const chartType = instellingen.grafiek || 'lijn';
const meldingenAan = instellingen.meldingen === true || instellingen.meldingen === 'true';

const translations = {
  nl: {
    spanning: 'Zonnepaneelspanning (V)',
    stroom: 'Zonnepaneelstroom (A)',
    temp: 'Binnen- en Buitentemperatuur (°C)',
    buiten: 'Buitentemperatuur',
    binnen: 'Binnentemperatuur',
    lucht: 'Luchtvochtigheid (%)',
    waterstof: 'Waterstofproductie (L/u)',
    accu: 'Accuniveau (%)',
    co2: 'CO₂-concentratie binnen (ppm)',
    tijd: 'Tijdstip',
    dashboardLoaded: 'Dashboard geladen!',
    toepassen: 'Toepassen',
    fout: 'Er is een fout opgetreden.'
  },
  en: {
    spanning: 'Solar panel voltage (V)',
    stroom: 'Solar panel current (A)',
    temp: 'Indoor and Outdoor Temperature (°C)',
    buiten: 'Outdoor temperature',
    binnen: 'Indoor temperature',
    lucht: 'Humidity (%)',
    waterstof: 'Hydrogen production (L/h)',
    accu: 'Battery level (%)',
    co2: 'CO₂ concentration indoor (ppm)',
    tijd: 'Time',
    dashboardLoaded: 'Dashboard loaded!',
    toepassen: 'Apply',
    fout: 'An error occurred.'
  }
};
const taal = instellingen.taal || 'nl';
const t = translations[taal];

function showNotification(message) {
  if (meldingenAan) {
    let n = document.createElement('div');
    n.textContent = message;
    n.style.position = 'fixed';
    n.style.top = '20px';
    n.style.right = '20px';
    n.style.background = '#4B2E83';
    n.style.color = 'white';
    n.style.padding = '16px 24px';
    n.style.borderRadius = '8px';
    n.style.zIndex = 9999;
    document.body.appendChild(n);
    setTimeout(() => n.remove(), 2500);
  }
}

const isPieAllowed = id => [
  'spanningChart',
  'stroomChart',
  'luchtChart',
  'waterstofChart',
  'accuChart',
  'co2Chart'
].includes(id);

const getChartType = id => {
  if (chartType === 'cirkel' && isPieAllowed(id)) return 'pie';
  if (chartType === 'staaf') return 'bar';
  return 'line';
};

fetch('http://localhost:3000')
  .then(res => res.json())
  .then(data => {
    console.log(data);
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
        type: getChartType(id),
        data: {
          labels: tijd,
          datasets: Array.isArray(data) && data[0]?.label ? data.map(ds => ({
            ...ds,
            backgroundColor: getChartType(id) === 'pie' ? [color || 'orange'] : 'rgba(0,0,0,0)',
            borderWidth: 2
          })) : [{
            label,
            data,
            borderColor: color,
            backgroundColor: getChartType(id) === 'pie' ? [color || 'orange'] : 'rgba(0,0,0,0)',
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          plugins: { legend: { display: true } },
          scales: getChartType(id) === 'pie' ? {} : {
            x: { display: true, title: { display: true, text: t.tijd } },
            y: { beginAtZero: false }
          }
        }
      });
    };

    makeChart('spanningChart', t.spanning, spanning, 'orange');
    makeChart('stroomChart', t.stroom, stroom, 'green');
    makeChart('tempChart', t.temp, [
      {
        label: t.buiten,
        data: buitenTemp,
        borderColor: 'blue'
      },
      {
        label: t.binnen,
        data: binnenTemp,
        borderColor: 'red'
      }
    ], null);
    makeChart('luchtChart', t.lucht, luchtvochtigheid, 'teal');
    makeChart('waterstofChart', t.waterstof, waterstof, 'purple');
    makeChart('accuChart', t.accu, accu, 'black');
    makeChart('co2Chart', t.co2, co2, 'gray');
    showNotification(t.dashboardLoaded);
  })
  .catch(() => showNotification(t.fout)); 