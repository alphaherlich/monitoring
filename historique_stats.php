<?php
session_start();
include __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/includes/db.php';

// Détection du filtre
$filtre = $_GET['filtre'] ?? 'jour';

$where = '';
switch ($filtre) {
    case 'semaine':
        $where = "WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        break;
    case 'mois':
        $where = "WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
        break;
    default:
        $where = "WHERE DATE(created_at) = CURDATE()";
}

// Récupération des données
$stmt = $pdo->query("SELECT DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') as date, cpu, ram, disk FROM system_stats $where ORDER BY created_at ASC");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>📊 Historique des statistiques</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(120deg, #e0f7fa, #f1f8e9);
            margin: 0;
            padding: 30px;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #00796b;
            margin-bottom: 30px;
            text-shadow: 1px 1px 2px #ccc;
        }

        .filter-buttons {
            text-align: center;
            margin-bottom: 20px;
        }

        .filter-buttons button {
            padding: 10px 18px;
            margin: 0 10px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-weight: bold;
            background-color: #26a69a;
            color: white;
            transition: background 0.3s ease;
        }

        .filter-buttons button:hover {
            background-color: #004d40;
        }

        canvas {
            display: block;
            margin: 0 auto;
            max-width: 1000px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 20px;
        }

        .export-btn {
            text-align: center;
            margin-top: 30px;
        }

        .export-btn a {
            background: #43a047;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 3px 5px rgba(0,0,0,0.2);
            transition: background 0.3s;
        }

        .export-btn a:hover {
            background: #1b5e20;
        }
    </style>
</head>
<body>

<h2>📈 Historique des Statistiques Système</h2>

<div class="filter-buttons">
    <button onclick="changerFiltre('jour')">Aujourd'hui</button>
    <button onclick="changerFiltre('semaine')">Cette semaine</button>
    <button onclick="changerFiltre('mois')">Ce mois</button>
</div>

<canvas id="chartHistorique" width="1000" height="420"></canvas>

<div class="export-btn">
    <a href="export_stats.php?filtre=<?= htmlspecialchars($filtre) ?>">📤 Exporter en CSV</a>
</div>

<script>
    const statsData = <?= json_encode($data) ?>;

    const labels = statsData.map(row => row.date);
    const cpuData = statsData.map(row => row.cpu);
    const ramData = statsData.map(row => row.ram);
    const diskData = statsData.map(row => row.disk);

    const ctx = document.getElementById('chartHistorique').getContext('2d');

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'CPU (%)',
                    data: cpuData,
                    borderColor: '#ef5350',
                    backgroundColor: 'rgba(239,83,80,0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'RAM (%)',
                    data: ramData,
                    borderColor: '#42a5f5',
                    backgroundColor: 'rgba(66,165,245,0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Disque (%)',
                    data: diskData,
                    borderColor: '#66bb6a',
                    backgroundColor: 'rgba(102,187,106,0.1)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 20
                    }
                },
                x: {
                    ticks: {
                        maxTicksLimit: 10,
                        autoSkip: true
                    }
                }
            }
        }
    });

    function changerFiltre(filtre) {
        window.location.href = '?filtre=' + filtre;
    }
</script>

</body>
</html>
