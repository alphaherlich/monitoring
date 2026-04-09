<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Requête sécurisée avec PDO
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :username LIMIT 1");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // ✅ Connexion réussie — on stocke l'utilisateur dans $_SESSION['user']
        $_SESSION['user'] = [
            'id' => $user['id'],
            'role' => $user['role'],
            'name' => $user['name']
        ];

        // ✅ Redirection vers le dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        // ❌ Identifiants incorrects
        $_SESSION['error'] = "Nom d'utilisateur ou mot de passe incorrect.";
        header("Location: login.php");
        exit();
    }
} else {
    // ❌ Requête directe sans POST
    header("Location: login.php");
    exit();
}
