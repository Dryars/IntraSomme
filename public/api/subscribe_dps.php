<?php
// Fichier : api/subscribe_dps.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
header('Content-Type: application/json');

// Récupération des données JSON
$input = json_decode(file_get_contents('php://input'), true);
$dps_id = $input['dps_id'] ?? null;
$team_id = $input['team_id'] ?? null;
$action = $input['action'] ?? 'join'; // 'join' ou 'leave'
$user_name = "Vous"; // Dans un vrai cas : $_SESSION['user_name']

if (!$dps_id || !$team_id) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
    exit;
}

if (!isset($_SESSION['dps_list'][$dps_id])) {
    echo json_encode(['success' => false, 'message' => 'DPS introuvable']);
    exit;
}

$dps = &$_SESSION['dps_list'][$dps_id]; // Passage par référence pour modifier directement la session
$target_team = null;
$target_key = null;

// Trouver l'équipe
foreach ($dps['teams'] as $key => &$team) {
    if ($team['id'] == $team_id) {
        $target_team = &$team;
        $target_key = $key;
        break;
    }
}

if (!$target_team) {
    echo json_encode(['success' => false, 'message' => 'Équipe introuvable']);
    exit;
}

if ($action === 'join') {
    // Vérification capacité
    if (count($target_team['members']) >= $target_team['capacity']) {
        echo json_encode(['success' => false, 'message' => 'Équipe complète']);
        exit;
    }
    // Vérifier si déjà inscrit
    if (in_array($user_name, $target_team['members'])) {
        echo json_encode(['success' => false, 'message' => 'Déjà inscrit dans cette équipe']);
        exit;
    }
    
    // Inscription
    $target_team['members'][] = $user_name;
    $message = "Inscription validée dans l'équipe " . $target_team['name'];

} elseif ($action === 'leave') {
    // Désinscription
    $key = array_search($user_name, $target_team['members']);
    if ($key !== false) {
        unset($target_team['members'][$key]);
        // Réindexer le tableau
        $target_team['members'] = array_values($target_team['members']); 
        $message = "Désinscription effectuée.";
    } else {
        echo json_encode(['success' => false, 'message' => 'Vous n\'êtes pas dans cette équipe']);
        exit;
    }
}

echo json_encode(['success' => true, 'message' => $message]);
?>  