<?php
session_start();

// Vérifie si l'utilisateur est connecté, sinon redirige vers le bon login.php
if (!isset($_SESSION['username'])) {
    // Correction : redirection relative correcte
    header('Location: /monitoring/login.php');
    exit();
}
?>
