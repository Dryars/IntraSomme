<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Accueil</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'menu.php'; ?>

<div class="content">
    <h1>Bienvenue sur le site</h1>

    <?php if($_SESSION['role'] >= 2): ?>
        <p>Contenu réservé aux modérateurs et administrateurs.</p>
    <?php endif; ?>

    <p>Vous pouvez accéder au <a href="calendrier.php">calendrier</a>.</p>
</div>

<script src="js/main.js"></script>
</body>
</html>
