<?php
require '../src/database.php';
require '../src/auth.php';
requireLogin();
$contacts = $pdo->query("SELECT * FROM contacts")->fetchAll();
echo json_encode($contacts);
