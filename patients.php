<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Connexion BDD
$host     = 'localhost';    // serveur local XAMPP
$dbname   = 'monitoring';   // le nom de la base importée
$username = 'root';         // utilisateur par défaut de XAMPP
$password = '';             // mot de passe vide par défaut de XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur connexion : " . $e->getMessage());
}

// Création table patients si non existante
$pdo->exec("
CREATE TABLE IF NOT EXISTS patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    date_naissance DATE,
    sexe VARCHAR(10),
    telephone VARCHAR(20),
    adresse VARCHAR(255),
    date_enregistrement DATETIME DEFAULT CURRENT_TIMESTAMP
)
");

// Traitement ajout avec revenu
if (isset($_POST['ajouter'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_naissance = $_POST['date_naissance'];
    $sexe = $_POST['sexe'];
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];
    $revenu = $_POST['revenu'];
    $devise = $_POST['devise']; // récupère la devise sélectionnée

    // Enregistrement du patient
    $stmt = $pdo->prepare("INSERT INTO patients (nom, prenom, date_naissance, sexe, telephone, adresse, date_enregistrement) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$nom, $prenom, $date_naissance, $sexe, $telephone, $adresse]);

    // Récupérer l'ID du patient ajouté
    $patient_id = $pdo->lastInsertId();

    // Enregistrer le revenu lié avec la devise
    $stmtRevenu = $pdo->prepare("INSERT INTO revenus (patient_id, montant, devise, date_enregistrement) VALUES (?, ?, ?, NOW())");
    $stmtRevenu->execute([$patient_id, $revenu, $devise]);

    echo "<p style='color:green;'>✅ Patient et revenu ajoutés avec succès !</p>";
}


// Traitement modification
if (isset($_POST['modifier'])) {
    $stmt = $pdo->prepare("UPDATE patients SET nom=?, prenom=?, date_naissance=?, sexe=?, telephone=?, adresse=? WHERE id=?");
    $stmt->execute([$_POST['nom'], $_POST['prenom'], $_POST['date_naissance'], $_POST['sexe'], $_POST['telephone'], $_POST['adresse'], $_POST['id']]);
    header("Location: patients.php");
    exit;
}

// Traitement suppression
if (isset($_GET['supprimer'])) {
    $stmt = $pdo->prepare("DELETE FROM patients WHERE id=?");
    $stmt->execute([$_GET['supprimer']]);
    header("Location: patients.php");
    exit;
}

// Récupérer patient à modifier si demandé
$editPatient = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM patients WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editPatient = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Liste des patients
$patients = $pdo->query("SELECT * FROM patients ORDER BY date_enregistrement DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion Patients</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f0f7f9;
        margin: 20px;
        color: #333;
    }
    h1, h2 {
        color: #2c6e49;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
        background: white;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }
    th {
        background-color: #4caf50;
        color: white;
    }
    tr:hover {background-color: #f1f1f1;}
    form {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 8px rgba(0,0,0,0.1);
        max-width: 600px;
    }
    input[type=text], input[type=date], select {
        width: 100%;
        padding: 10px;
        margin: 6px 0 16px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    button {
        background-color: #4caf50;
        color: white;
        padding: 10px 25px;
        border: none;
        border-radius: 25px;
        cursor: pointer;
        font-size: 1rem;
    }
    button:hover {
        background-color: #45a049;
    }
    .btn-danger {
        background-color: #e74c3c;
        padding: 6px 12px;
        border-radius: 20px;
        color: white;
        text-decoration: none;
    }
    .btn-danger:hover {
        background-color: #c0392b;
    }
    .btn-secondary {
        background-color: #3498db;
        padding: 6px 12px;
        border-radius: 20px;
        color: white;
        text-decoration: none;
    }
    .btn-secondary:hover {
        background-color: #2980b9;
    }
    .actions a {
        margin: 0 4px;
        font-size: 0.9rem;
    }
    .return-btn a {
        display: inline-block;
        margin-bottom: 20px;
        background: #ccc;
        padding: 8px 15px;
        border-radius: 20px;
        color: #333;
        text-decoration: none;
    }
    .return-btn a:hover {
        background: #999;
        color: white;
    }
</style>
</head>
<body>

<header>
    <h1>🧑‍⚕️ Gestion des Patients</h1>
</header>

<div class="return-btn">
    <a href="dashboard_health.php">⬅ Retour au Dashboard Santé</a>
</div>

<?php if ($editPatient): ?>
    <h2>✏️ Modifier Patient ID #<?= htmlspecialchars($editPatient['id']) ?></h2>
    <form method="post">
        <input type="hidden" name="id" value="<?= htmlspecialchars($editPatient['id']) ?>">
        <label>Nom</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($editPatient['nom']) ?>" required>
        <label>Prénom</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($editPatient['prenom']) ?>" required>
        <label>Date de naissance</label>
        <input type="date" name="date_naissance" value="<?= htmlspecialchars($editPatient['date_naissance']) ?>">
        <label>Sexe</label>
        <select name="sexe">
            <option value="" <?= $editPatient['sexe']==='' ? 'selected' : '' ?>>--</option>
            <option value="Homme" <?= $editPatient['sexe']==='Homme' ? 'selected' : '' ?>>Homme</option>
            <option value="Femme" <?= $editPatient['sexe']==='Femme' ? 'selected' : '' ?>>Femme</option>
        </select>
        <label>Téléphone</label>
        <input type="text" name="telephone" value="<?= htmlspecialchars($editPatient['telephone']) ?>">
        <label>Adresse</label>
        <input type="text" name="adresse" value="<?= htmlspecialchars($editPatient['adresse']) ?>">
        <button type="submit" name="modifier">Modifier</button>
        <a href="patients.php" style="margin-left: 10px;">Annuler</a>
    </form>
<?php else: ?>
 <h2>➕ Ajouter un patient</h2>
<form method="post">
    <label>Nom</label>
    <input type="text" name="nom" placeholder="Nom" required>

    <label>Prénom</label>
    <input type="text" name="prenom" placeholder="Prénom" required>

    <label>Date de naissance</label>
    <input type="date" name="date_naissance">

    <label>Sexe</label>
    <select name="sexe">
        <option value="">--</option>
        <option value="Homme">Homme</option>
        <option value="Femme">Femme</option>
    </select>

    <label>Téléphone</label>
    <input type="text" name="telephone" placeholder="Téléphone">

    <label>Adresse</label>
    <input type="text" name="adresse" placeholder="Adresse">

    <label for="revenu">Revenu (€ / CFA / $)</label>
    <input type="number" step="0.01" name="revenu" id="revenu" required>

    <label for="devise">Devise</label>
    <select name="devise" id="devise" required>
        <option value="€">€ Euro</option>
        <option value="CFA">CFA</option>
        <option value="$">$ Dollar</option>
    </select>

    <button type="submit" name="ajouter">Ajouter</button>
</form>


<?php endif; ?>

<h2>📋 Liste des patients</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Date Naissance</th>
            <th>Sexe</th>
            <th>Téléphone</th>
            <th>Adresse</th>
            <th>Date Enregistrement</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($patients as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['id']) ?></td>
            <td><?= htmlspecialchars($p['nom']) ?></td>
            <td><?= htmlspecialchars($p['prenom']) ?></td>
            <td><?= htmlspecialchars($p['date_naissance']) ?></td>
            <td><?= htmlspecialchars($p['sexe']) ?></td>
            <td><?= htmlspecialchars($p['telephone']) ?></td>
            <td><?= htmlspecialchars($p['adresse']) ?></td>
            <td><?= htmlspecialchars($p['date_enregistrement']) ?></td>
            <td class="actions">
                <a href="?edit=<?= $p['id'] ?>" class="btn-secondary">Modifier</a>
                <a href="?supprimer=<?= $p['id'] ?>" class="btn-danger" onclick="return confirm('Supprimer ce patient ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
