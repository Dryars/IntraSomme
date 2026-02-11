<?php
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    // echo $email;
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    // var_dump($stmt);
    // $stmt->debugDumpParams();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // echo password_hash($mot_de_passe, PASSWORD_BCRYPT);

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        if ($user['email_validated'] != 1) {
            $error = "Votre email n'est pas encore validé.";
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email_validated'] = $user['email_validated'];
            header("Location: index.php");
            exit;
        }
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion - CRM IntraSomme</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body class="login-body">

<div class="login-wrapper">
    <div class="login-card">
        <div class="logo">CRM IntraSomme</div>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Adresse email" required>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
            <button type="submit">Connexion</button>
        </form>
    </div>
</div>

<script src="js/login.js"></script>
</body>
</html>
