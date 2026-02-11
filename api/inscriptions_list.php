<?php
require '../src/database.php';

// Change la base de données à 'dpss'
$pdo->exec("USE dpss");

header('Content-Type: application/json');

try {
    // Archiver les DPS terminés depuis plus de 14 jours
    $pdo->prepare("UPDATE dps SET archived=1 WHERE statut='termine' AND end_date < DATE_SUB(NOW(), INTERVAL 14 DAY)")->execute();

    // Récupérer DPS actifs
    $stmt = $pdo->query("SELECT * FROM dps WHERE archived=0");
    $dpsList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer équipes et postes pour chaque DPS
    foreach($dpsList as &$dps) {
        $stmtTeams = $pdo->prepare("SELECT * FROM teams WHERE dps_id=?");
        $stmtTeams->execute([$dps['id']]);
        $dps['teams'] = $stmtTeams->fetchAll(PDO::FETCH_ASSOC);

        $stmtPosts = $pdo->prepare("SELECT * FROM posts WHERE dps_id=?");
        $stmtPosts->execute([$dps['id']]);
        $dps['posts'] = $stmtPosts->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode($dpsList);
} catch (Exception $e) {
    // En cas d'erreur, renvoyer un JSON vide
    echo json_encode([]);
}
