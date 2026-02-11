<?php
require __DIR__ . '/../../../../src/database.php';

$pole = $_GET['pole'] ?? '';

$stmt = $pdo->prepare("SELECT id, title, start, end FROM events WHERE pole = ?");
$stmt->execute([$pole]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($events);
