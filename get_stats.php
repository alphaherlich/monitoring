<?php
session_start();
header('Content-Type: application/json');

// Génération simulée car sys_getloadavg et /proc/meminfo sont bloqués sur InfinityFree
$cpu = rand(1, 30) + (rand(0, 100) / 100); // ex : 12.58
$ram = rand(20, 80) + (rand(0, 100) / 100);
$disk = rand(30, 90) + (rand(0, 100) / 100);

echo json_encode([
    'cpu' => round($cpu, 2),
    'ram' => round($ram, 2),
    'disk' => round($disk, 2)
]);
?>
