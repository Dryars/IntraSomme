<?php
require '../src/database.php';
session_start();
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
$stmt->execute([$email]);
$user = $stmt->fetch();
if($user && password_verify($password, $user['mot_de_passe'])){
    if(!$user['email_validated']) { http_response_code(403); echo "Email non validé"; exit; }
    $_SESSION['user_id']=$user['id'];
    $_SESSION['role']=$user['role'];
    $_SESSION['email_validated']=$user['email_validated'];
    echo json_encode(['ok'=>1]);
} else { http_response_code(401); echo "Login incorrect"; }
