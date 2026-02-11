<?php
// Fichier : api/calendar_events.php

// Inclus le fichier de connexion à la BDD
require __DIR__ . '/../src/database.php';

// Définit l'en-tête pour indiquer que la réponse est du JSON
header('Content-Type: application/json');

try {
    // Récupère les données des événements
    $stmt = $pdo->prepare("SELECT id, title, start, end, all_day FROM events");
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Formate les données pour FullCalendar
    $fullCalendarEvents = array_map(function($row){
        return [
            "id" => $row['id'],
            "title" => $row['title'],
            "start" => $row['start'],
            "end" => $row['end'],
            // Convertit 1/0 en booléen true/false
            "allDay" => $row['all_day'] == 1, 
            "color" => "#F97316" // Couleur d'événement par défaut
        ];
    }, $events);

    // Renvoie le résultat en JSON
    echo json_encode($fullCalendarEvents);

} catch (PDOException $e) {
    // En cas d'erreur BDD
    http_response_code(500);
    // Renvoie un tableau vide pour que le calendrier ne plante pas
    echo json_encode([]); 
}