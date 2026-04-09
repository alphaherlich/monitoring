<?php
session_start();
require_once 'auth_check.php'; // Protège l'accès

header('Content-Type: application/json');

// Charge CPU (1 min)
$cpuLoad = sys_getloadavg()[0];

// RAM (% utilisée)
$ramUsage = (function () {
    $data = explode("\n", file_get_contents("/proc/meminfo"));
    $memTotal = (int) filter_var($data[0], FILTER_SANITIZE_NUMBER_INT);
    $memFree  = (int) filter_var($data[1], FILTER_SANITIZE_NUMBER_INT);
    $used     = $memTotal - $memFree;
    return round($used / $memTotal * 100, 2);
})();

// Disque (% libre)
$diskUsage = round(disk_free_space("/") / disk_total_space("/") * 100, 2);

$stats = [
    'cpu' => $cpuLoad,
    'ram' => $ramUsage,
    'disk' => $diskUsage
];

echo json_encode($stats);
?>
