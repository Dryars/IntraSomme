<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Liste des pôles et images associées
$poles = [
    ['name'=>'Formation','file'=>'poles/pole_formation.php','img'=>'images/formation.webp'],
    ['name'=>'Logistique Nautique','file'=>'poles/pole_logistique_nautique.php','img'=>'images/logistique_nautique.jpg'],
    ['name'=>'Habillement','file'=>'poles/pole_habillement.php','img'=>'images/habillement.jpg'],
    ['name'=>'Communication','file'=>'poles/pole_communication.php','img'=>'images/communication.jpg'],
    ['name'=>'Logistique Véhicule','file'=>'poles/pole_logistique_vehicule.php','img'=>'images/logistique_vehicule.jpg'],
    ['name'=>'DPS','file'=>'poles/pole_dps.php','img'=>'images/dps.jpg'],
    ['name'=>'Affectations','file'=>'poles/pole_affectations.php','img'=>'images/affectations.jpg'],
    ['name'=>'Weekend','file'=>'poles/pole_weekend.php','img'=>'images/weekend.jpg'],
    ['name'=>'Relations Médias','file'=>'poles/pole_relations_medias.php','img'=>'images/relations_medias.jpg'],
    ['name'=>'Délégué Départemental','file'=>'poles/pole_delegue_departemental.php','img'=>'images/delegue_departemental.jpg'],
    ['name'=>'Trésorier','file'=>'poles/pole_tresorier.php','img'=>'images/tresorier.jpeg'],
    ['name'=>'Direction','file'=>'poles/pole_direction.php','img'=>'images/direction.jpeg'],
    ['name'=>'Siège','file'=>'poles/pole_siege.php','img'=>'images/siege.jpeg'],
    ['name'=>'Intranet','file'=>'poles/pole_intranet.php','img'=>'images/intranet.jpg']
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Pôles - IntraSomme</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>

<div class="pole-container"> <!-- Utiliser la classe correcte -->
    <?php foreach($poles as $pole): ?>
        <a href="<?= $pole['file'] ?>" class="pole-card">
            <img src="<?= $pole['img'] ?>" alt="<?= htmlspecialchars($pole['name']) ?>">
            <div class="pole-name"><?= htmlspecialchars($pole['name']) ?></div>
        </a>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
