<?php
require '../src/database.php';
require '../src/auth.php';
requireLogin();
$id = intval($_GET['id'] ?? 0);
$bil = $pdo->prepare("SELECT * FROM bilans WHERE id=?");
$bil->execute([$id]);
echo json_encode($bil->fetch());
