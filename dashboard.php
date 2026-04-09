<?php
session_start();
include __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/includes/db.php';


// Charger la liste des applications supervisées
$hosts = include __DIR__ . '/config_hosts.php';

// Fonction pour vérifier HTTP (tu peux copier celle de apps_status.php)
function checkHTTP(string $url): int {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    $result = curl_exec($ch);
    if ($result === false) {
        curl_close($ch);
        return 0;
    }
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $httpCode ?: 0;
}

// Compter les applications actives
$activeApps = 0;
foreach ($hosts as $host) {
    $httpCode = checkHTTP($host['url']);
    if ($httpCode >= 200 && $httpCode < 400) {
        $activeApps++;
    }
}



$role = $_SESSION['role'] ?? 'user';
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && $role === 'admin') {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $user_role = $_POST["role"];

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
    $stmt->execute([
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'role' => $user_role
    ]);
    $message = "✅ Utilisateur ajouté avec succès.";
}



?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard | Monitoring</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
    <style>
    .card {
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      background: #fff;
      margin-bottom: 20px;
      transition: all 0.3s ease;
    }

    .card:hover {
      box-shadow: 0 6px 12px rgba(0,0,0,0.15);
      background-color: #f0f9ff;
      cursor: pointer;
    }
  </style>

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f9fafb;
      margin: 0;
      padding: 0;
      color: #333;
    }

    .container-fluid {
      display: flex;
    }

    .sidebar {
      width: 220px;
      background-color: #1e293b;
      padding: 20px;
      color: #fff;
      height: 100vh;
      position: fixed;
    }

    .sidebar h4 {
      margin-top: 0;
    }

    .sidebar a {
      display: block;
      color: #cbd5e1;
      text-decoration: none;
      padding: 10px 0;
      border-bottom: 1px solid #334155;
    }

    .sidebar a.active, .sidebar a:hover {
      color: #fff;
      font-weight: bold;
    }

    main {
      margin-left: 240px;
      padding: 30px;
      width: 100%;
    }

    .card {
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
      margin-bottom: 20px;
    }

    .card h5 {
      margin-top: 0;
    }

    .alert {
      background-color: #e0f2fe;
      padding: 15px;
      border-left: 5px solid #0284c7;
      border-radius: 5px;
      margin-bottom: 20px;
    }

    .alert-success {
      background-color: #dcfce7;
      padding: 10px;
      border-left: 5px solid #22c55e;
      margin-top: 10px;
    }

    input, select {
      width: 100%;
      padding: 8px;
      margin: 5px 0 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      background-color: #3b82f6;
      color: white;
      padding: 10px 18px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    button:hover {
      background-color: #2563eb;
    }

    footer {
      margin-top: 50px;
      text-align: center;
      color: #999;
    }

    /* MODALE */
    #testModal, #statsModal {
      display:none;
      position:fixed;
      top:0;
      left:0;
      width:100%;
      height:100%;
      background-color:rgba(0,0,0,0.6);
      z-index:9999;
    }

    #testModal .modal-content, #statsModal .modal-content {
      background:#fff;
      width:90%;
      max-width:1000px;
      margin:80px auto;
      padding:20px;
      border-radius:10px;
      position:relative;
    }

    .modal-close {
      position:absolute;
      top:10px;
      right:10px;
      background:red;
      color:white;
      border:none;
      padding:5px 10px;
      border-radius:5px;
    }

  </style>
</head>
<body>
  <div class="container-fluid">
    <nav class="sidebar">
      <h4>📊 Monitoring</h4>
      <a href="#" class="active">🏠 Tableau de bord</a>
      <a href="monitor.php">📡 Supervision</a>
      <?php if ($role === 'admin'): ?>
        <a href="register.php">➕ Ajouter un utilisateur</a>
        <a href="user/index.php">👥 Gérer les utilisateurs</a>
      <?php endif; ?>
      <a href="logout.php">🚪 Déconnexion</a>
    </nav>

    <main>
      <h2>Bienvenue sur le tableau de bord</h2>
      <p>Vous êtes connecté en tant que : <strong><?= htmlspecialchars($role) ?></strong></p>

      <div class="alert">
        ✅ Supervision réseau / système / applications<br>
        ✅ Alertes en temps réel<br>
        ✅ Données par agents<br>
        ✅ Visualisation graphique
      </div>
<div class="card" style="cursor: pointer;" onclick="location.href='apps_status.php'">
  <h5>🌐 Statut réseau (Ping)</h5>
  <canvas id="chart1" height="150"></canvas>
  <p id="ping-info">Cliquez pour voir le détail de la supervision réseau & applications</p>
</div>


        <div class="card">
  <a href="dashboard_health.php" style="text-decoration: none; color: inherit;">
  <div style="background-color: #f5f5f5; border-radius: 12px; padding: 15px; box-shadow: 0 0 10px rgba(0,0,0,0.1); transition: 0.3s;">
    <h5 style="margin-bottom: 5px;">🧠 Santé système</h5>
    <p id="system-health" style="margin: 0; color: #666;">Cliquez pour voir les statistiques santé</p>
  </div>
<div class="card" style="padding: 20px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); background: #fff; margin-bottom: 20px;">
  <h5 style="margin-top: 0; margin-bottom: 15px;">🧹 Supervision d'applications</h5>
  <p>Applications actives : <strong><?= $activeApps ?></strong></p>
  <p>Status global : 
    <?php if ($activeApps === count($hosts)): ?>
      <span style="color: green; font-weight: bold;">Tous les systèmes fonctionnent</span>
    <?php elseif ($activeApps === 0): ?>
      <span style="color: red; font-weight: bold;">Aucune application active</span>
    <?php else: ?>
      <span style="color: orange; font-weight: bold;">Partiellement fonctionnel</span>
    <?php endif; ?>
  </p>
  <a href="apps_status.php" 
     style="display: inline-block; margin-top: 10px; padding: 8px 16px; border: 2px solid #27ae60; border-radius: 25px; color: #27ae60; text-decoration: none; font-weight: 600; transition: background-color 0.3s ease;">
    Voir détails
  </a>
</div>



        <div class="card">
          <h5>📊 Statistiques système (CPU, RAM, Disque)</h5>
          <canvas id="sysChart" height="200"></canvas>
        </div>

        <!-- Modale planification -->
        <div class="card">
          <h5>📝 Planification des tests</h5>
          <button onclick="openTestModal()">➕ Voir les tests planifiés</button>
        </div>

        <!-- Modale historique -->
        <div class="card">
  <h5>📈 Historique des statistiques</h5>
  <button onclick="openStatsModal()" style="background-color:#10b981;">📊 Voir l’historique</button>
  
  <!-- Bouton Export CSV ajouté ici -->
  <div style="margin-top:15px;">
    <a href="export_stats.php?filtre=jour"
       style="display:inline-block; background:#43a047; color:#fff; padding:10px 18px; border-radius:6px; text-decoration:none; font-weight:bold;">
       📤 Exporter les données CSV (Aujourd'hui)
    </a>
  </div>
</div>


      <?php if ($role === 'admin'): ?>
      <form method="post" novalidate>
        <h4>➕ Ajouter un utilisateur</h4>
        <?php if (!empty($message)): ?>
          <div class="alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <label for="name">Nom</label>
        <input type="text" name="name" id="name" required />

        <label for="email">Email</label>
        <input type="email" name="email" id="email" required />

        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" required />

        <label for="role">Rôle</label>
        <select name="role" id="role">
          <option value="user">Utilisateur</option>
          <option value="admin">Administrateur</option>
        </select>

        <button type="submit">Créer l'utilisateur</button>
      </form>
      <?php endif; ?>

      <footer>© 2025 - Monitoring App. Tous droits réservés</footer>
    </main>
  </div>

  <!-- Modale Tests -->
  <div id="testModal">
    <div class="modal-content">
      <button class="modal-close" onclick="closeTestModal()">✖</button>
      <h4>📋 Tests planifiés</h4>
      <iframe src="plan_tests.php" width="100%" height="400" style="border:none;"></iframe>
    </div>
  </div>

  <!-- Modale Historique -->
  <div id="statsModal">
    <div class="modal-content">
      <button class="modal-close" onclick="closeStatsModal()">✖</button>
      <h4>📈 Historique des statistiques système</h4>
      <iframe src="historique_stats.php" width="100%" height="500" style="border:none;"></iframe>
    </div>
  </div>

  <script>
    async function fetchStats() {
      try {
        const res = await fetch('get_stats.php');
        const data = await res.json();
        return {
          labels: ['CPU %', 'RAM %', 'Disque libre %'],
          datasets: [{
            label: 'Utilisation Système',
            data: [data.cpu, data.ram, data.disk],
            backgroundColor: ['#3b82f6', '#10b981', '#facc15']
          }]
        };
      } catch (e) {
        console.error("Erreur stats:", e);
        return null;
      }
    }

    async function renderSystemChart() {
      const ctx = document.getElementById('sysChart').getContext('2d');
      const chartData = await fetchStats();

      if (chartData) {
        new Chart(ctx, {
          type: 'bar',
          data: chartData,
          options: {
            scales: {
              y: {
                beginAtZero: true,
                max: 100
              }
            }
          }
        });
      }
    }

    function openTestModal() {
      document.getElementById('testModal').style.display = 'block';
    }

    function closeTestModal() {
      document.getElementById('testModal').style.display = 'none';
    }

    function openStatsModal() {
      document.getElementById('statsModal').style.display = 'block';
    }

    function closeStatsModal() {
      document.getElementById('statsModal').style.display = 'none';
    }

    renderSystemChart();
    setInterval(() => {
      fetch('save_stats.php');
    }, 300000);
  </script>
</body>
</html>
