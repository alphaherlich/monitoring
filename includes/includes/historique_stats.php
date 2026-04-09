<?php
// Connexion à la base InfinityFree
$host     = 'sql105.infinityfree.com';
$dbname   = 'if0_38866205_monitoring';
$username = 'if0_38866205';
$password = 'pazVgSp8WaN';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupérer les données
$stmt = $pdo->prepare("SELECT timestamp, cpu, ram, disk FROM system_stats ORDER BY timestamp DESC LIMIT 100");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$timestamps = [];
$cpuData = [];
$ramData = [];
$diskData = [];

foreach (array_reverse($rows) as $row) {
    $timestamps[] = date("d/m H:i", strtotime($row['timestamp']));
    $cpuData[] = $row['cpu'];
    $ramData[] = $row['ram'];
    $diskData[] = $row['disk'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des Statistiques</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f2f2f2;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        canvas {
            background: #fff;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .chart-container {
            width: 95%;
            max-width: 1000px;
            margin: 30px auto;
        }
    </style>
</head>
<body>
    <h2>📊 Historique des Statistiques Système</h2>

    <div class="chart-container">
        <canvas id="historyChart"></canvas>
    </div>

    <script>
        const ctx = document.getElementById("historyChart").getContext("2d");

        const historyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($timestamps) ?>,
                datasets: [
                    {
                        label: "CPU (%)",
                        data: <?= json_encode($cpuData) ?>,
                        borderColor: "rgba(255, 99, 132, 1)",
                        backgroundColor: "rgba(255, 99, 132, 0.2)",
                        fill: false
                    },
                    {
                        label: "RAM (%)",
                        data: <?= json_encode($ramData) ?>,
                        borderColor: "rgba(54, 162, 235, 1)",
                        backgroundColor: "rgba(54, 162, 235, 0.2)",
                        fill: false
                    },
                    {
                        label: "DISQUE (%)",
                        data: <?= json_encode($diskData) ?>,
                        borderColor: "rgba(255, 206, 86, 1)",
                        backgroundColor: "rgba(255, 206, 86, 0.2)",
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    </script>
</body>
</html>
