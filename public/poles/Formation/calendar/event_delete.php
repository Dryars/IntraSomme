<?php
require __DIR__ . '/../../../../src/database.php';
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['id'])) {
    $stmt = $pdo->prepare("DELETE FROM events WHERE id=?");
    $stmt->execute([$data['id']]);
    echo json_encode(["success"=>true, "message"=>"Événement supprimé"]);
} else {
    echo json_encode(["success"=>false, "message"=>"ID manquant"]);
}
