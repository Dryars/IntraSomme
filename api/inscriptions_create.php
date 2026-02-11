<?php
require '../src/database.php';
require '../src/auth.php';
requireLogin();
if(!isEmailValidated()){ http_response_code(403); echo "Email non validé"; exit; }

$data = json_decode(file_get_contents('php://input'), true);
$stmt = $pdo->prepare("INSERT INTO inscriptions(dps_id,user_id,equipe_id) VALUES(?,?,?)");
$stmt->execute([
    $data['dps_id'],
    $_SESSION['user_id'],
    $data['equipe_id'] ?? null
]);
echo json_encode(['ok'=>1]);
