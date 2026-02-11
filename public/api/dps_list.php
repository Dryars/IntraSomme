<?php
// Fichier : api/dps_list.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
header('Content-Type: application/json');

// Initialisation des données si elles n'existent pas encore en session
if (!isset($_SESSION['dps_list'])) {
    $_SESSION['dps_list'] = [
        1 => [ 'id' => 1, 'title' => 'Trail des Illaminés 2025', 'lieu' => 'Parc Saint-Pierre', 'lat' => 49.9018, 'lng' => 2.3135, 'chef' => 'H. DUPONT', 'canal' => '04', 'statut' => 'en_cours', 'date' => '05/12/2025' ],
        2 => [ 'id' => 2, 'title' => 'Concert Zénith : Indochine', 'lieu' => 'Zénith Amiens', 'lat' => 49.8945, 'lng' => 2.2650, 'chef' => 'M. DURAND', 'canal' => '08', 'statut' => 'prepare', 'date' => '18/12/2025' ],
        3 => [ 'id' => 3, 'title' => 'Match ASC vs Bordeaux', 'lieu' => 'Stade de la Licorne', 'lat' => 49.8938, 'lng' => 2.2635, 'chef' => 'L. MARTIN', 'canal' => '02', 'statut' => 'non_prepare', 'date' => '20/12/2025' ],
        4 => [ 'id' => 4, 'title' => 'Réderie de la Hotoie', 'lieu' => 'Parc de la Hotoie', 'lat' => 49.8960, 'lng' => 2.2850, 'chef' => 'S. LEFEBVRE', 'canal' => '06', 'statut' => 'prepare', 'date' => '01/04/2025' ],
        5 => [ 'id' => 5, 'title' => 'Gala de Boxe', 'lieu' => 'Cirque Jules Verne', 'lat' => 49.8905, 'lng' => 2.2980, 'chef' => 'P. BERNARD', 'canal' => '05', 'statut' => 'termine', 'date' => '10/11/2025' ],
        6 => [ 'id' => 6, 'title' => 'Feu d\'Artifice 14 Juillet', 'lieu' => 'Plan d\'eau', 'lat' => 49.9025, 'lng' => 2.3150, 'chef' => 'J. DUBOIS', 'canal' => '01', 'statut' => 'non_prepare', 'date' => '14/07/2025' ],
        7 => [ 'id' => 7, 'title' => 'Course Cycliste GP Somme', 'lieu' => 'Boulevard Faidherbe', 'lat' => 49.8890, 'lng' => 2.3020, 'chef' => 'C. PETIT', 'canal' => '09', 'statut' => 'en_cours', 'date' => '15/05/2025' ],
        8 => [ 'id' => 8, 'title' => 'Festival de la BD', 'lieu' => 'Halle Freyssinet', 'lat' => 49.8850, 'lng' => 2.3100, 'chef' => 'M. LEROY', 'canal' => '03', 'statut' => 'prepare', 'date' => '02/06/2025' ],
        9 => [ 'id' => 9, 'title' => 'Marché de Noël', 'lieu' => 'Rue des 3 Cailloux', 'lat' => 49.8920, 'lng' => 2.2995, 'chef' => 'A. MOREAU', 'canal' => '07', 'statut' => 'en_cours', 'date' => '24/12/2025' ],
        10 => [ 'id' => 10, 'title' => 'Cross Scolaire', 'lieu' => 'Stade Urbain', 'lat' => 49.9100, 'lng' => 2.2900, 'chef' => 'F. ROUSSEL', 'canal' => '04', 'statut' => 'non_prepare', 'date' => '12/03/2025' ]
    ];
}

// Renvoie la liste sous forme de tableau indexé (pour le JS)
echo json_encode(array_values($_SESSION['dps_list']));
?>