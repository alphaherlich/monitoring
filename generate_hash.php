<?php
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Mot de passe : $password<br>";
echo "Hash généré : $hash<br>";
echo "Test vérification : ";
var_dump(password_verify($password, $hash));
