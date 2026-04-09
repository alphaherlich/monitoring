<?php
session_start();
require_once 'includes/db.php';

// Si déjà connecté, redirection vers le dashboard
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if ($username && $name && $email && $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (username, name, email, password, role) 
                               VALUES (:username, :name, :email, :password, 'user')");

        try {
            $stmt->execute([
                'username' => $username,
                'name'     => $name,
                'email'    => $email,
                'password' => $hashedPassword
            ]);
            $success = "✅ Inscription réussie. Vous pouvez vous connecter.";
        } catch (PDOException $e) {
            $error = "❌ Erreur : " . $e->getMessage();
        }
    } else {
        $error = "❗ Tous les champs sont obligatoires.";
    }
}

if (isset($_POST['guest'])) {
    $_SESSION['username'] = "visiteur";
    $_SESSION['role'] = "guest";
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 500px;">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">📝 Inscription</h3>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label>Nom d'utilisateur</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Nom complet</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary w-100">S'inscrire</button>
                </form>

                <hr class="my-4">

                <form method="post">
                    <button type="submit" name="guest" class="btn btn-outline-secondary w-100">🔓 Accès visiteur (guest)</button>
                </form>

                <div class="mt-3 text-center">
                    <a href="login.php">Déjà un compte ? Se connecter</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
