<?php
// Fichier : chart_stats.php
session_start();
require_once 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Statistiques Système</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background: #f4f4f4;
    }
    h2 {
      text-align: center;
    }
    canvas {
      background: white;
      padding: 10px;
      border-radius: 10px;
      box-shadow: 0 0 10px #ccc;
    }
  </style>
</head>
<body>
  <h2>Statistiques système (historique)</h2>
  <canvas id="myChart" height="100"></canvas>

  <script>
    fetch('get_stats.php')
      .then(res => res.json())
      .then(data => {
        const labels = data.map(d => d.created_at);
        const cpu = data.map(d => d.cpu);
        const ram = data.map(d => d.ram);
        const disk = data.map(d => d.disk);

        new Chart(document.getElementById('myChart'), {
          type: 'line',
          data: {
            labels: labels,
            datasets: [
              { label: 'CPU (%)', data: cpu, borderColor: 'red', fill: false },
              { label: 'RAM (%)', data: ram, borderColor: 'blue', fill: false },
              { label: 'Disque (%)', data: disk, borderColor: 'green', fill: false }
            ]
          },
          options: {
            responsive: true,
            plugins: {
              legend: { position: 'top' },
              title: { display: true, text: 'Historique des performances système' }
            }
          }
        });
      });
  </script>
</body>
</html>
