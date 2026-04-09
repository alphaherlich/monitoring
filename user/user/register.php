<?php
session_start();

// Inclure les fichiers nécessaires
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_check.php';

// Vérifie si l'utilisateur est connecté et est admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    // Redirection vers le tableau de bord ou la connexion
    header('Location: ../dashboard.php'); // ou login.php si non connecté
    exit();
}
?>
