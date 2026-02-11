<?php
require __DIR__ . '/../../../../src/database.php';

header('Content-Type: application/json');

try {
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? '';
    $start = $_POST['start'] ?? '';
    $end = $_POST['end'] ?? '';
    $description = $_POST['description'] ?? '';

    if(!$title || !$start || !$end){
        echo json_encode(["success"=>false,"message"=>"Veuillez remplir tous les champs"]);
        exit;
    }

    if($id){
        // Modifier
        $stmt = $pdo->prepare("UPDATE events SET title=?, start=?, end=?, description=? WHERE id=?");
        $stmt->execute([$title, $start, $end, $description, $id]);
        echo json_encode(["success"=>true,"message"=>"Événement modifié"]);
    } else {
        // Ajouter
        $stmt = $pdo->prepare("INSERT INTO events (title,start,end,description,pole) VALUES (?,?,?,?,?)");
        $stmt->execute([$title,$start,$end,$description,'bnssa']);
        echo json_encode(["success"=>true,"message"=>"Événement ajouté"]);
    }

} catch (Exception $e) {
    echo json_encode(["success"=>false,"message"=>$e->getMessage()]);
}
