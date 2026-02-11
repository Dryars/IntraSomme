<?php
// Fichier : api/dps_list.php
header('Content-Type: application/json');

// Simulation de données (À remplacer plus tard par une requête SQL SELECT)
$dps_data = [
    [
        'id' => 1,
        'title' => 'Trail des Illaminés',
        'date' => '05/12/2025',
        'lieu' => 'Parc Saint-Pierre, Amiens',
        'statut' => 'en_cours', // non_prepare, prepare, en_cours, termine
        'lat' => 49.9018, // Centre de la carte
        'lng' => 2.3135,
        'description' => 'Dispositif de nuit, prévoir éclairage.',
        'teams' => [
            ['name' => 'Équipe Alpha (Pédestre)', 'lat' => 49.9025, 'lng' => 2.3140],
            ['name' => 'Équipe Bravo (VTT)', 'lat' => 49.9010, 'lng' => 2.3120]
        ],
        'posts' => [
            ['name' => 'PMA Principal', 'lat' => 49.9018, 'lng' => 2.3135, 'inventory' => 'Lot A, DSA, Oxygène'],
            ['name' => 'Poste de Signaleur 1', 'lat' => 49.9030, 'lng' => 2.3150, 'inventory' => 'Radio, Trousse Bobologie']
        ]
    ],
    [
        'id' => 2,
        'title' => 'Concert Zénith',
        'date' => '18/12/2025',
        'lieu' => 'Zénith Amiens',
        'statut' => 'prepare',
        'lat' => 49.8945,
        'lng' => 2.2650,
        'description' => 'Concert grand public, risque mouvement de foule.',
        'teams' => [
            ['name' => 'Binôme 1 (Fosse)', 'lat' => 49.8946, 'lng' => 2.2652],
            ['name' => 'Binôme 2 (Gradins)', 'lat' => 49.8944, 'lng' => 2.2648]
        ],
        'posts' => [
            ['name' => 'Infirmerie Fixe', 'lat' => 49.8942, 'lng' => 2.2645, 'inventory' => 'Lit, DSA, Tensiomètre']
        ]
    ]
];

echo json_encode($dps_data);
?>