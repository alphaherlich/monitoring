<?php
// Fichier : login.php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/includes/db.php';

$error = '';

// Connexion visiteur
if (isset($_GET['guest']) && $_GET['guest'] === '1') {
    $_SESSION['user'] = [
        'id' => null,
        'username' => 'invite',
        'role' => 'guest',
        'name' => 'Visiteur'
    ];
    header("Location: dashboard.php");
    exit;
}

// Connexion utilisateur
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :identifier OR email = :identifier LIMIT 1");
            $stmt->execute(['identifier' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                    'name' => $user['name']
                ];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            $error = "Erreur : " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: #fff;
            padding: 35px 25px;
            border-radius: 10px;
            width: 350px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
            color: #333;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }
        button, .guest-btn, .register-link {
            width: 100%;
            padding: 12px;
            font-size: 15px;
            border-radius: 6px;
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
            display: inline-block;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
        }
        button:hover {
            background-color: #0056b3;
        }
        .guest-btn {
            background-color: #6c757d;
            color: white;
            border: none;
        }
        .guest-btn:hover {
            background-color: #5a6268;
        }
        .register-link {
            background-color: transparent;
            color: #007BFF;
            border: none;
            font-size: 14px;
        }
        .register-link:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Connexion</h2>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="username">Nom d'utilisateur ou Email :</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Mot de passe :</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Se connecter</button>
        </form>

        <a href="login.php?guest=1" class="guest-btn">Connexion visiteur</a>
        <a href="register.php" class="register-link">Créer un compte</a>
    </div>
</body>
</html>
