<?php
require '../src/database.php';
require '../src/auth.php';
requireLogin();

$id = intval($_GET['id'] ?? 0);
if($id){
    $pole = $pdo->prepare("SELECT * FROM poles WHERE id=?");
    $pole->execute([$id]);
    $p = $pole->fetch();
    $docs = $pdo->prepare("SELECT * FROM pole_docs WHERE pole_id=?");
    $docs->execute([$id]);
    $p['docs'] = $docs->fetchAll();
    echo json_encode($p);
}else{
    $list = $pdo->query("SELECT * FROM poles")->fetchAll();
    echo json_encode($list);
}
