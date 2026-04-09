<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Connexion BDD
$host     = 'localhost';    // serveur local XAMPP
$dbname   = 'monitoring';   // le nom de la base importée
$username = 'root';         // utilisateur par défaut de XAMPP
$password = '';             // mot de passe vide par défaut de XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur connexion : " . $e->getMessage());
}

// 🌍 Devise dynamique
$devise = '€'; // Ou 'CFA', '$', etc.


// Filtre période (jour/semaine/mois)
$filtre = $_GET['filtre'] ?? 'jour';
$interval = "1 DAY";
if ($filtre === 'semaine') $interval = "7 DAY";
if ($filtre === 'mois') $interval = "30 DAY";
// Connexion PDO ici...

// Connexion PDO ici...

// Récupération des totaux et statistiques
$totalPatients = $pdo->query("SELECT COUNT(*) FROM patients")->fetchColumn();
$totalRevenus = $pdo->query("SELECT COALESCE(SUM(montant),0) FROM revenus WHERE date_enregistrement >= NOW() - INTERVAL $interval")->fetchColumn();
$totalTests = $pdo->query("SELECT COUNT(*) FROM tests WHERE test_date >= NOW() - INTERVAL $interval")->fetchColumn();

$revenusParDevise = $pdo->query("SELECT devise, SUM(montant) as total FROM revenus GROUP BY devise")->fetchAll(PDO::FETCH_ASSOC);


// Autres requêtes et logique ici...
// Définition de l'intervalle (par exemple 7 jours ou selon ta variable $interval)
$interval = '7 DAY';  // ou récupère ça dynamiquement selon ton interface

// Récupérer tous les tests/rendez-vous des 7 derniers jours
$testsRecents = $pdo->query("SELECT * FROM tests WHERE test_date >= CURDATE() - INTERVAL 7 DAY")->fetchAll(PDO::FETCH_ASSOC);

// Calculer le total des tests récents (sur la période définie)
$totalTests = $pdo->query("SELECT COUNT(*) FROM tests WHERE test_date >= NOW() - INTERVAL $interval")->fetchColumn();

// Données graphiques : patients inscrits par jour
$graphPatients = $pdo->query("
    SELECT DATE(date_enregistrement) as jour, COUNT(*) as nb
    FROM patients
    WHERE date_enregistrement >= NOW() - INTERVAL $interval
    GROUP BY jour
    ORDER BY jour ASC
")->fetchAll(PDO::FETCH_ASSOC);

// Données graphiques : revenus par jour
$graphRevenus = $pdo->query("
    SELECT DATE(date_enregistrement) as jour, COALESCE(SUM(montant),0) as total
    FROM revenus
    WHERE date_enregistrement >= NOW() - INTERVAL $interval
    GROUP BY jour
    ORDER BY jour ASC
")->fetchAll(PDO::FETCH_ASSOC);
// Données graphiques : tests par jour
$graphTests = $pdo->query("
    SELECT test_date as jour, COUNT(*) as nb
    FROM tests
    WHERE test_date >= NOW() - INTERVAL $interval
    GROUP BY test_date
    ORDER BY test_date ASC
")->fetchAll(PDO::FETCH_ASSOC);


// Préparation des labels (dates) pour graphique (fusion pour garder même labels)
$allDates = [];
foreach ($graphPatients as $row) $allDates[$row['jour']] = true;
foreach ($graphRevenus as $row) $allDates[$row['jour']] = true;
foreach ($graphTests as $row) $allDates[$row['jour']] = true;
$labels = array_keys($allDates);
sort($labels);

// Fonctions pour récupérer les valeurs par date (ou 0)
function getValByDate($data, $date, $field) {
    foreach ($data as $row) {
        if ($row['jour'] === $date) return (float)$row[$field];
    }
    return 0;
}

// Construction des datasets pour JS
$dataPatients = [];
$dataRevenus = [];
$dataTests = [];
foreach ($labels as $date) {
    $dataPatients[] = getValByDate($graphPatients, $date, 'nb');
    $dataRevenus[] = getValByDate($graphRevenus, $date, 'total');
    $dataTests[] = getValByDate($graphTests, $date, 'nb');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard Santé</title>
<style>
  /* ==== Style intégré ==== */
  @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');
  body {
    font-family: 'Roboto', sans-serif;
    margin: 0; padding: 0;
    background: #e9f0f3;
    color: #34495e;
  }
  header {
    background: #27ae60;
    color: white;
    padding: 20px 30px;
    text-align: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.15);
  }
  header h1 {
    margin: 0;
    font-weight: 700;
    font-size: 1.8rem;
  }
  .container {
    max-width: 1100px;
    margin: 30px auto;
    padding: 0 15px;
  }
  .btn {
    display: inline-block;
    padding: 10px 20px;
    border-radius: 25px;
    text-decoration: none;
    color: white;
    font-weight: 600;
    transition: background-color 0.3s ease;
  }
  .btn-primary { background: #27ae60; }
  .btn-primary:hover { background: #1e8449; }
  .btn-secondary { background: #2980b9; }
  .btn-secondary:hover { background: #1c5980; }
  .btn-outline {
    background: transparent;
    border: 2px solid #34495e;
    color: #34495e;
  }
  .btn-outline:hover {
    background: #34495e;
    color: white;
  }
  .top-buttons {
    display: flex;
    justify-content: space-between;
    margin-bottom: 30px;
  }
  .stats-cards {
    display: flex;
    gap: 25px;
    flex-wrap: wrap;
    margin-bottom: 40px;
  }
  .card {
    background: white;
    flex: 1 1 30%;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    text-align: center;
  }
  .card h2 {
    margin-bottom: 15px;
    font-size: 1.4rem;
    color: #27ae60;
  }
  .big-number {
    font-size: 2.8rem;
    font-weight: 700;
    color: #2c3e50;
  }
  .filters {
    margin-bottom: 25px;
    font-weight: 600;
  }
  select {
    padding: 6px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 1rem;
  }
  canvas {
    background: white;
    border-radius: 15px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    max-width: 100%;
  }
  footer {
    text-align: center;
    padding: 15px;
    color: #7f8c8d;
    font-size: 0.9rem;
    margin-top: 50px;
  }
  @media (max-width: 800px) {
    .stats-cards {
      flex-direction: column;
    }
    .card {
      flex: none;
      width: 100%;
    }
    .top-buttons {
      flex-direction: column;
      gap: 15px;
    }
  }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.card table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

.card table th,
.card table td {
  border: 1px solid #ccc;
  padding: 8px;
  text-align: left;
}

.card table th {
  background-color: #f0f0f0;
}

.card table tbody tr:hover {
  background-color: #e8f4ff;
}
</style>



</head>
<body>

<header>
    <h1>📊 Tableau de bord Santé</h1>
</header>

<div class="container">

  <div class="top-buttons">
    <a href="dashboard.php" class="btn btn-outline">⬅ Retour au Dashboard principal</a>
    <div>
     <div class="top-buttons">
    <a href="patients.php" class="btn btn-primary">Gestion Patients</a>
    <a href="plan_tests.php" class="btn btn-secondary">📋 Planification Santé</a>
    <button class="btn btn-outline" onclick="window.print()">🖨 Imprimer</button>
</div>

    
    </div>
  </div>

  <div class="filters">
    <label for="filtre">Filtrer par :</label>
    <select id="filtre" onchange="location = '?filtre=' + this.value;">
      <option value="jour" <?= $filtre === 'jour' ? 'selected' : '' ?>>Jour</option>
      <option value="semaine" <?= $filtre === 'semaine' ? 'selected' : '' ?>>Semaine</option>
      <option value="mois" <?= $filtre === 'mois' ? 'selected' : '' ?>>Mois</option>
    </select>
  </div>

  <div class="stats-cards">
    <div class="card">
      <h2>🧑‍⚕️ Patients</h2>
      <p class="big-number"><?= $totalPatients ?></p>
    </div>
   <div class="card">
  <h2>💰 Revenus</h2>
  <?php foreach ($revenusParDevise as $rev): ?>
    <p class="big-number"><?= number_format($rev['total'], 2, ',', ' ') ?> <?= htmlspecialchars($rev['devise']) ?></p>
  <?php endforeach; ?>
</div>

    <div class="card">
  <h2>🧪 Tests récents (7 derniers jours)</h2>
  <table>
    <thead>
      <tr><th>ID</th><th>Title</th><th>Date Test</th></tr>
    </thead>
    <tbody>
      <?php foreach($testsRecents as $test): ?>
      <tr>
        <td><?= htmlspecialchars($test['id']) ?></td>
        <td><?= htmlspecialchars($test['title']) ?></td>
        <td><?= htmlspecialchars($test['test_date']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>


  <canvas id="statsChart" height="140"></canvas>

</div>

<script>
const ctx = document.getElementById('statsChart').getContext('2d');
const statsChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [
            {
                label: 'Patients',
                data: <?= json_encode($dataPatients) ?>,
                borderColor: '#27ae60',
                backgroundColor: 'rgba(39, 174, 96, 0.3)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
            },
            {
                label: 'Revenus (€)',
                data: <?= json_encode($dataRevenus) ?>,
                borderColor: '#2980b9',
                backgroundColor: 'rgba(41, 128, 185, 0.3)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
            },
            {
                label: 'Tests / RDV',
                data: <?= json_encode($dataTests) ?>,
                borderColor: '#c0392b',
                backgroundColor: 'rgba(192, 57, 43, 0.3)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        stacked: false,
        plugins: {
            legend: { position: 'top' },
            tooltip: { mode: 'index', intersect: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

<footer>
    &copy; <?= date('Y') ?> Santé Dashboard
</footer>

</body>
</html>
