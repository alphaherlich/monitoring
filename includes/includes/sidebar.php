<!-- includes/sidebar.php -->
<nav class="col-md-2 d-none d-md-block sidebar bg-dark text-white">
  <div class="position-sticky pt-3">
    <h4 class="px-3">Monitoring</h4>
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link<?=basename($_SERVER['SCRIPT_NAME'])=='dashboard.php'?' active':''?>" href="../dashboard.php"><i class="bi bi-speedometer2"></i> Tableau de bord</a></li>
      <li class="nav-item"><a class="nav-link<?=basename($_SERVER['SCRIPT_NAME'])=='monitor.php'?' active':''?>" href="../monitor.php"><i class="bi bi-hdd-network"></i> Supervision</a></li>
      <?php if ($_SESSION['user']['role']==='admin'): ?>
      <li class="nav-item"><a class="nav-link<?=strpos($_SERVER['SCRIPT_NAME'],'user/')!==false?' active':''?>" href="../user/index.php"><i class="bi bi-people"></i> Utilisateurs</a></li>
      <?php endif; ?>
      <li class="nav-item"><a class="nav-link text-danger" href="../logout.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
    </ul>
  </div>
</nav>
