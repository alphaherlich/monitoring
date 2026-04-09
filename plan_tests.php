<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $date = $_POST['date'] ?? '';
    if ($title && $date) {
        $stmt = $pdo->prepare("INSERT INTO tests (title, test_date) VALUES (?, ?)");
        $stmt->execute([$title, $date]);
    }
}

$tests = $pdo->query("SELECT * FROM tests ORDER BY test_date ASC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Planification des Tests</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f4f4f4; }
        h2 { color: #333; }
        form, table { background: #fff; padding: 15px; margin-top: 20px; border-radius: 8px; }
        input, button { padding: 8px; margin: 5px 0; width: 100%; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>
    <h2>📋 Planification des Tests</h2>
    <form method="post">
        <label>Titre du test :</label>
        <input type="text" name="title" required>
        <label>Date prévue :</label>
        <input type="date" name="date" required>
        <button type="submit">Ajouter</button>
    </form>

    <h3>📆 Tests planifiés :</h3>
    <table>
        <tr><th>Titre</th><th>Date</th></tr>
        <?php foreach ($tests as $test): ?>
        <tr>
            <td><?= htmlspecialchars($test['title']) ?></td>
            <td><?= htmlspecialchars($test['test_date']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
