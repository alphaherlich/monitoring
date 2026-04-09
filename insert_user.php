<?php
// Ce fichier est juste un script temporaire pour ajouter un utilisateur à la base
$host = 'localhost';
$dbname = 'monitoring';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Création d'un utilisateur exemple
    $username = 'admin';
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $email = 'admin@example.com';
    $name = 'Admin';
    $role = 'admin';

    $stmt = $pdo->prepare("INSERT INTO users (username, email, name, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$username, $email, $name, $password, $role]);

    echo "✅ Utilisateur ajouté avec succès.";
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
