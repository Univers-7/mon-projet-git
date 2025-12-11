<?php
[cite_start]// 1. Configuration de la Base de Données [cite: 27-32]
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'todolist');
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');

try {
    // Connexion à la base de données via PDO
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";port=" . DB_PORT, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

[cite_start]// 2. Traitement des formulaires (POST) [cite: 58]
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    [cite_start]// Action: Ajouter une nouvelle tâche [cite: 60]
    if ($_POST['action'] === 'new' && !empty($_POST['title'])) {
        $stmt = $pdo->prepare("INSERT INTO todo (title) VALUES (:title)");
        $stmt->execute(['title' => $_POST['title']]);
    }
    
    [cite_start]// Action: Supprimer une tâche [cite: 61]
    elseif ($_POST['action'] === 'delete' && !empty($_POST['id'])) {
        $stmt = $pdo->prepare("DELETE FROM todo WHERE id = :id");
        $stmt->execute(['id' => $_POST['id']]);
    }
    
    [cite_start]// Action: Basculer l'état (Fait / Pas fait) [cite: 62]
    elseif ($_POST['action'] === 'toggle' && !empty($_POST['id'])) {
        [cite_start]// Astuce donnée dans le document [cite: 63]
        $stmt = $pdo->prepare("UPDATE todo SET done = 1 - done WHERE id = :id");
        $stmt->execute(['id' => $_POST['id']]);
    }
    
    // Pour éviter de renvoyer le formulaire si on actualise la page
    header("Location: index.php");
    exit;
}

[cite_start]// 3. Récupération des tâches (Lecture) [cite: 55]
[cite_start]// Triées par date de création du plus récent au plus ancien [cite: 57]
$stmt = $pdo->query("SELECT * FROM todo ORDER BY created_at DESC");
$taches = $stmt->fetchAll(PDO::FETCH_ASSOC); [cite_start]// Stockage dans la variable $taches [cite: 56]
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TodoList</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <h1>Ma TodoList</h1>
    </div>

</body>
</html>