<?php
require '../src/database.php';
$data = json_decode(file_get_contents('php://input'), true);
$message = $data['message'] ?? 'Notification';
$tokens = $pdo->query("SELECT token FROM push_tokens")->fetchAll(PDO::FETCH_COLUMN);

$serverKey = getenv('FCM_SERVER_KEY');
$payload = [
    'registration_ids'=>$tokens,
    'notification'=>['title'=>'INTRASOMME','body'=>$message]
];

$ch = curl_init("https://fcm.googleapis.com/fcm/send");
curl_setopt($ch,CURLOPT_HTTPHEADER,[
    'Authorization:key='.$serverKey,
    'Content-Type:application/json'
]);
curl_setopt($ch,CURLOPT_POST,true);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($payload));
$response = curl_exec($ch);
curl_close($ch);
echo $response;
