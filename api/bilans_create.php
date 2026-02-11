<?php
require '../src/database.php';
require '../src/auth.php';
requireLogin();
$data = json_decode(file_get_contents('php://input'), true);

$stmt = $pdo->prepare("INSERT INTO bilans(dps_id,equipe_id,symptomes,actions,constantes,formulaire) VALUES(?,?,?,?,?,?)");
$stmt->execute([
    $data['dps_id'] ?? null,
    $_SESSION['user_id'],
    $data['symptomes'] ?? '',
    $data['actions'] ?? '',
    json_encode($data['constantes'] ?? []),
    json_encode($data['formulaire'] ?? [])
]);
echo json_encode(['ok'=>1]);
