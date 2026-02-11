<?php
require '../src/database.php';
session_start();
$data = json_decode(file_get_contents('php://input'), true);
$lat = $data['lat'] ?? null;
$lng = $data['lng'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

if($lat && $lng && $user_id){
    $stmt = $pdo->prepare("UPDATE equipes SET latitude=?,longitude=?,updated_at=NOW() WHERE id=(SELECT equipe_id FROM users WHERE id=?)");
    $stmt->execute([$lat,$lng,$user_id]);
    echo json_encode(['ok'=>1]);
}
