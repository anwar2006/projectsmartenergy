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
          datasets: Array.isArray(data) && data[0]?.label ? data.map(ds => ({
            ...ds,
            backgroundColor: 'rgba(0,0,0,0)',
            borderWidth: 2
          })) : [{
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