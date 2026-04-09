<?php
$host = 'localhost';
$dbname = 'monitoring';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $stmt = $pdo->query("SELECT name FROM users");
    foreach ($stmt as $row) {
        echo "Utilisateur : " . $row['name'] . "<br>";
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
