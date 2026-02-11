<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'superadmin';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Inscriptions DPS</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header><h1>Inscriptions DPS</h1></header>
<div class="card">
    <ul>
        <li>Équipe A - Inscrite</li>
        <li>Équipe B - En attente</li>
    </ul>
</div>
<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
