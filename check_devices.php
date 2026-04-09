<?php
// Connexion à la base de données (ajuste si besoin)
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/send_alert.php';

// Requête pour détecter les équipements en état critique
$sql = "SELECT * FROM devices WHERE status IN ('offline', 'warning')";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$devices = $stmt->fetchAll();

// Si au moins un équipement est en anomalie
if (count($devices) > 0) {
    $message = "<h3>🚨 Alertes sur vos équipements</h3><ul>";

    foreach ($devices as $device) {
        $message .= "<li><strong>{$device['name']}</strong> [{$device['ip']}] est <b>{$device['status']}</b> depuis le {$device['last_update']}</li>";
    }

    $message .= "</ul><p>Merci de vérifier dans les plus brefs délais.</p>";

    // Sujet de l'email
    $sujet = "Alerte monitoring : équipements en anomalie";

    // Envoi du mail
    if (envoyerAlerte($sujet, $message)) {
        echo "✅ Alerte envoyée avec succès à l’administrateur.";
    } else {
        echo "❌ Échec de l’envoi de l’alerte.";
    }
} else {
    echo "✅ Aucun équipement en anomalie.";
}
?>
