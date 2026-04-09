<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil | ALPHA HERLICH Monitoring</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Style général */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: rgba(0, 0, 0, 0.6);
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 0 30px rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.1);
            position: relative;
            animation: glowBorder 5s linear infinite;
        }

        @keyframes glowBorder {
            0% { box-shadow: 0 0 20px #ff0055; }
            25% { box-shadow: 0 0 20px #00ffd5; }
            50% { box-shadow: 0 0 20px #ff00f7; }
            75% { box-shadow: 0 0 20px #00ff66; }
            100% { box-shadow: 0 0 20px #ff0055; }
        }

        .rainbow-text {
            font-size: 32px;
            font-weight: bold;
            background: linear-gradient(45deg, red, orange, yellow, green, blue, indigo, violet);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            animation: rainbow 6s linear infinite;
        }

        @keyframes rainbow {
            0% { background-position: 0%; }
            100% { background-position: 100%; }
        }

        h1 {
            font-size: 26px;
            margin-bottom: 10px;
        }

        .emblem-box {
            border: 3px solid #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(255, 255, 255, 0.5);
            margin-bottom: 20px;
        }

        .btn-login {
            display: inline-block;
            padding: 12px 30px;
            font-size: 16px;
            color: #fff;
            text-decoration: none;
            background: linear-gradient(90deg, #00f2fe, #4facfe);
            border: none;
            border-radius: 25px;
            box-shadow: 0 0 15px #00f2fe;
            transition: all 0.4s ease;
            cursor: pointer;
        }

        .btn-login:hover {
            background: linear-gradient(90deg, #ff416c, #ff4b2b);
            box-shadow: 0 0 25px #ff416c;
            transform: scale(1.05);
        }

        p {
            font-size: 18px;
            margin-top: 10px;
        }

        @media screen and (max-width: 768px) {
            .login-container {
                width: 90%;
                padding: 20px;
            }

            .rainbow-text {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="emblem-box">
            <h1>Bienvenue dans la plateforme <span class="rainbow-text">ALPHA HERLICH</span> Prime de MONITORING</h1>
        </div>
        <p>Veuillez vous connecter pour accéder à la supervision</p>
        <a href="login.php" class="btn-login">🚀 Se connecter</a>
    </div>

</body>
</html>
