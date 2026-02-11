<?php
require '../src/database.php';
session_start();
$data = json_decode(file_get_contents('php://input'), true);
$token = $data['token'] ?? '';
$user_id = $_SESSION['user_id'] ?? null;
if($token){
    $stmt = $pdo->prepare("INSERT IGNORE INTO push_tokens(token,user_id) VALUES(?,?)");
    $stmt->execute([$token,$user_id]);
    echo json_encode(['ok'=>1]);
}
