<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">🔍 Monitoring</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Tableau de bord</a></li>
                    <li class="nav-item"><a class="nav-link" href="monitor.php">Supervision</a></li>
                    <li class="nav-item"><a class="nav-link" href="user/index.php">Utilisateurs</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Déconnexion</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Se connecter</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<!-- includes/navbar.php -->
<nav class="navbar">
    <div class="navbar-brand">
        <h1>🔍 Monitoring</h1>
    </div>
    <ul class="navbar-menu">
        <li><a href="/monitoring/dashboard.php">Tableau de bord</a></li>
        <li><a href="/monitoring/monitor.php">Supervision</a></li>
        <li><a href="/monitoring/user/index.php">Utilisateurs</a></li>
        <li><a href="/monitoring/logout.php">Déconnexion</a></li>
    </ul>
</nav>
