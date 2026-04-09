<?php
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Mot de passe : $password<br>";
echo "Hash généré : $hash";
?>
