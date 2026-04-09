<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth_check.php';

$hosts = include __DIR__ . '/config_hosts.php';

function pingHost(string $ip): string {
    return '✅ Non disponible sur InfinityFree';
}

function checkHTTP(string $url): int {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0'); // pour GitHub
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
    <title>Supervision en ligne</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #eef1f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 960px;
            margin: auto;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 20px;
            margin-bottom: 20px;
            border-left: 8px solid #007BFF;
        }
        .card h3 {
            margin-top: 0;
            color: #0056b3;
        }
        .status {
            margin: 8px 0;
            font-size: 1.1em;
        }
        .status span {
            font-weight: bold;
        }
        .green {
            color: #28a745;
        }
        .red {
            color: #dc3545;
        }
        footer {
            text-align: center;
            color: #777;
            margin-top: 40px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>📊 Supervision en ligne (InfinityFree)</h2>

    <?php foreach ($hosts as $host): 
        $httpCode = checkHTTP($host['url']);
        $snmpStatus = getSNMPStatus($host['snmp_host'], $host['snmp_community']);
        ?>
        <div class="card">
            <h3><?= htmlspecialchars($host['name']) ?></h3>
            <div class="status">🌐 IP : <span><?= htmlspecialchars($host['ip']) ?></span></div>
            <div class="status">✅ Ping : <span>Non disponible sur InfinityFree</span></div>
            <div class="status">🌍 HTTP : 
                <span class="<?= ($httpCode === 0 || $httpCode >= 400) ? 'red' : 'green' ?>">
                    <?= $httpCode ? "🟢 HTTP $httpCode OK" : "🔴 Erreur" ?>
                </span>
            </div>
            <div class="status">📡 SNMP :
                <span class="<?= str_contains($snmpStatus, '🟢') ? 'green' : 'red' ?>">
                    <?= $snmpStatus ?>
                </span>
            </div>
        </div>
    <?php endforeach; ?>

    <footer>© 2025 - Monitoring System</footer>
</div>
</body>
</html>
