<?php
session_start();
require_once __DIR__ . '/includes/db.php';

// Vérifier l'accès
if (!isset($_SESSION['role'])) {
    die("⛔ Accès refusé.");
}

// Récupération du filtre
$filtre = $_GET['filtre'] ?? 'jour';
$where = "";

switch ($filtre) {
    case 'semaine':
        $where = "WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        break;
    case 'mois':
        $where = "WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
        break;
    default:
        $where = "WHERE DATE(created_at) = CURDATE()";
        $filtre = "jour"; // Pour nom de fichier propre
}

// Récupérer les données formatées
$stmt = $pdo->prepare("SELECT DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') AS date, cpu, ram, disk FROM system_stats $where ORDER BY created_at ASC");
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Préparer le téléchargement
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=stats_export_' . $filtre . '.csv');

// Ouverture du flux
$output = fopen('php://output', 'w');

// Écrire les en-têtes
fputcsv($output, ['Date/Heure', 'CPU (%)', 'RAM (%)', 'Disque (%)']);

// Écrire les données
foreach ($data as $row) {
    fputcsv($output, [
        $row['date'],
        $row['cpu'],
        $row['ram'],
        $row['disk']
    ]);
}

fclose($output);
exit;
