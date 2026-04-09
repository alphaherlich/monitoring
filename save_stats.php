<?php
session_start();
require_once 'includes/db.php'; // connexion PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpu = $_POST['cpu'] ?? null;
    $ram = $_POST['ram'] ?? null;
    $disk = $_POST['disk'] ?? null;

    if ($cpu && $ram && $disk) {
        $stmt = $pdo->prepare("INSERT INTO stats (cpu, ram, disk, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$cpu, $ram, $disk]);
        echo "OK";
    } else {
        echo "Données incomplètes.";
    }
}
?>
