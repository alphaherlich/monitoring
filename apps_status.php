<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth_check.php';

$hosts = include __DIR__ . '/config_hosts.php';

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

function getSNMPStatus(string $host, string $community, string $oid = "1.3.6.1.2.1.1.3.0"): string {
    if (!function_exists("snmpget")) return '❌ SNMP non supporté';
    $result = @snmpget($host, $community, $oid);
    return $result !== false ? '🟢 OK' : '🔴 Erreur SNMP';
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Statut des applications supervisées</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f8fa;
            margin: 0; padding: 20px;
        }
        h2 {
            text-align: center;
            color: #222;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f9ff;
        }
        .status-green {
            color: #28a745;
            font-weight: bold;
        }
        .status-red {
            color: #dc3545;
            font-weight: bold;
        }
        .btn-refresh {
            display: block;
            margin: 20px auto 40px auto;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-refresh:hover {
            background-color: #0056b3;
        }
        footer {
            text-align: center;
            color: #666;
            margin-top: 60px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div style="padding: 15px; background: #f0f0f0; border-bottom: 1px solid #ccc; margin-bottom: 20px;">
  <a href="dashboard.php" style="text-decoration: none; color: #007BFF; font-weight: bold;">
    ← Retour au Dashboard principal
  </a>
</div>



<h2>🖥️ Statut des applications supervisées</h2>

<button class="btn-refresh" onclick="location.reload()">🔄 Rafraîchir</button>

<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>IP</th>
            <th>HTTP</th>
            <th>SNMP</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($hosts as $host):
            $httpCode = checkHTTP($host['url']);
            $snmpStatus = getSNMPStatus($host['snmp_host'], $host['snmp_community']);
        ?>
        <tr>
            <td><?= htmlspecialchars($host['name']) ?></td>
            <td><?= htmlspecialchars($host['ip']) ?></td>
            <td class="<?= ($httpCode === 0 || $httpCode >= 400) ? 'status-red' : 'status-green' ?>">
                <?= $httpCode ? "🟢 HTTP $httpCode" : "🔴 Indisponible" ?>
            </td>
            <td class="<?= str_contains($snmpStatus, '🟢') ? 'status-green' : 'status-red' ?>">
                <?= $snmpStatus ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<footer>© 2025 - Monitoring System</footer>

</body>
</html>
