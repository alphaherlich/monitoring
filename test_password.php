<?php
$hash = '$2y$10$9NKb9lEYvEmNWUNrGLmXk.ApmUSd1P/0pUeNaK9QxeyrA55XYH6pi'; // ton hash actuel
$input = 'admin123';

if (password_verify($input, $hash)) {
    echo "✅ Le mot de passe est valide.";
} else {
    echo "❌ Mauvais mot de passe.";
}
