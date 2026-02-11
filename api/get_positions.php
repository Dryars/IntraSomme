<?php
require '../src/database.php';
$eqs = $pdo->query("SELECT id, nom, latitude as lat, longitude as lng FROM equipes")->fetchAll();
echo json_encode($eqs);
