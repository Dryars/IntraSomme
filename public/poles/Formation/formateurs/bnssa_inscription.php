<?php
require __DIR__ . '/../../../../src/database.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$event_id = $_POST['event_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO formateurs_inscriptions (event_id, formateur_id) VALUES (?,?)");
    $stmt->execute([$event_id, $_SESSION['user_id']]);
    $msg = "Vous êtes inscrit comme formateur pour cet événement !";
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Inscription Formateur BNSSA</title>
<link rel="stylesheet" href="/INTRASOMME/public/css/style.css">
</head>
<body>

<header><h1>S’inscrire comme formateur BNSSA</h1></header>

<?php if (!empty($msg)): ?>
<div class="alert-success"><?= $msg ?></div>
<?php endif; ?>

<form method="POST">
    <button type="submit">Je m'inscris</button>
</form>

</body>
</html>
