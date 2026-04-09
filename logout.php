<?php
session_start();

// Nettoie toutes les variables de session
$_SESSION = [];

// Supprime la session côté serveur
session_unset();
session_destroy();

// Redirection vers la page de connexion
header("Location: login.php");
exit;
?>
