<?php
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Mot de passe saisi : $password<br>";
echo "Hash généré : $hash<br>";

$result = password_verify($password, $hash);
echo "Résultat de password_verify avec hash généré : ";
var_dump($result);
