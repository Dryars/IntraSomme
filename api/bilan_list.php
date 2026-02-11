<?php
require '../src/database.php';
require '../src/auth.php';
requireLogin();
$bilans = $pdo->query("SELECT * FROM bilans ORDER BY created_at DESC")->fetchAll();
echo json_encode($bilans);
