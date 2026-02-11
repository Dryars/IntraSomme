<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'superadmin';
$id = $_GET['id'] ?? 1;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Vue du Pôle</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header><h1>Vue Pôle #<?= htmlspecialchars($id) ?></h1></header>
<div class="card">
    <h2>Documents et Sections</h2>
    <ul>
        <li>Document 1</li>
        <li>Document 2</li>
    </ul>
</div>
</body>
</html>
