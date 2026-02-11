<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Pôle Formation - CRM IntraSomme</title>
<link rel="stylesheet" href="/INTRASOMME/public/css/style.css">
</head>
<body>

<?php include __DIR__ . '/../header.php'; ?>

<div class="pole-container">

    <a class="pole-card" href="/INTRASOMME/public/poles/formation/BNSSA.php">
        <img src="/INTRASOMME/public/images/bnssa.png" alt="BNSSA">
        <h3>BNSSA</h3>
    </a>

    <a class="pole-card" href="/INTRASOMME/public/poles/formation/PSE.php">
        <img src="/INTRASOMME/public/images/pse.png" alt="PSE">
        <h3>PSE</h3>
    </a>

    <a class="pole-card" href="/INTRASOMME/public/poles/formation/SSA.php">
        <img src="/INTRASOMME/public/images/ssa.png" alt="SSA">
        <h3>SSA</h3>
    </a>

    <a class="pole-card" href="/INTRASOMME/public/poles/formation/PCOT.php">
        <img src="/INTRASOMME/public/images/permiscotier.png" alt="Permis côtier">
        <h3>PERMIS CÔTIER</h3>
    </a>

    <a class="pole-card" href="/INTRASOMME/public/poles/formation/JSNSM.php">
        <img src="/INTRASOMME/public/images/jeunes_snsm.png" alt="Jeunes SNSM">
        <h3>JEUNES SNSM</h3>
    </a>

    <a class="pole-card" href="/INTRASOMME/public/poles/formation/MJ.php">
        <img src="/INTRASOMME/public/images/marine_jet.png" alt="Marine Jet">
        <h3>MARINE JET</h3>
    </a>

</div>

<div class="contacts">
    <h2>Contacts Responsables</h2>

    <div class="contact">
        <strong>Jean Dupont</strong> – BNSSA – jean.dupont@intrasomme.org
    </div>

    <div class="contact">
        <strong>Marie Durand</strong> – PSE – marie.durand@intrasomme.org
    </div>

    <div class="contact">
        <strong>Luc Martin</strong> – SSA – luc.martin@intrasomme.org
    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>

<script src="/INTRASOMME/public/js/pole.js"></script>
</body>
</html>
