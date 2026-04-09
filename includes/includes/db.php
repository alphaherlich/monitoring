<?php
$host     = 'sql105.infinityfree.com';
$dbname   = 'if0_38866205_monitoring';
$username = 'if0_38866205';
$password = 'pazVgSp8WaN';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connexion réussie à la base de données.";
} catch(PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
