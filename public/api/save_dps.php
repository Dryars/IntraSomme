<?php
// Fichier : api/save_dps.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
header('Content-Type: application/json');

// Récupération des données JSON envoyées par le JS
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Données invalides']);
    exit;
}

// Création du nouvel ID
$new_id = count($_SESSION['dps_list']) + 1;

// Construction de l'objet DPS
$new_dps = [
    'id' => $new_id,
    'title' => $input['title'] ?? 'Sans titre',
    'lieu' => $input['location'] ?? 'Amiens',
    'date' => $input['start_date'] ?? date('Y-m-d'),
    'lat' => 49.894, // Par défaut si pas de map
    'lng' => 2.295,
    'chef' => 'Vous (Admin)', // Par défaut pour la démo
    'canal' => '01',
    'statut' => 'non_prepare',
    'description' => $input['description'] ?? '',
    'posts' => $input['posts'] ?? [],
    'teams' => $input['teams'] ?? [],
    'materiel' => $input['gear'] ?? []
];

// Si des postes sont définis, on prend le premier comme centre de carte
if (!empty($new_dps['posts'])) {
    $new_dps['lat'] = $new_dps['posts'][0]['lat'];
    $new_dps['lng'] = $new_dps['posts'][0]['lng'];
}

// Sauvegarde en session
$_SESSION['dps_list'][$new_id] = $new_dps;

echo json_encode(['success' => true, 'id' => $new_id]);
?>