<?php
$host     = 'localhost';
$dbname   = 'monitoring';   // nom de ta base locale importée
$username = 'root';          // utilisateur par défaut XAMPP
$password = '';              // mot de passe vide par défaut

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connexion réussie à la base de données locale.";
} catch(PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>