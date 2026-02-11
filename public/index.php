<?php
// Fichier : index.php

// 1. Démarrage de session sécurisé (Mock pour le développement)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Simulation des données de session si non existantes
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['role'] = 'superadmin';
    $_SESSION['user_name'] = 'Hadrien';
}

// 2. Inclusion de l'en-tête (Navigation + début HTML)
include __DIR__ . '/header.php'; 

// --- DONNÉES SIMULÉES POUR LE DASHBOARD ---
// Dans une vraie application, ces données viendraient de requêtes SQL (ex: COUNT(*) FROM benevoles)
$stats = [
    'benevoles_actifs' => 124,
    'dps_avenir' => 8,
    'formations_cours' => 3, // Sessions en cours
    'heures_mission' => 2450 // Cumul annuel
];

$prochains_dps = [
    ['date' => '05/12', 'nom' => 'Trail des Illaminés', 'lieu' => 'Amiens', 'effectif' => '4/6', 'status' => 'warning'],
    ['date' => '12/12', 'nom' => 'Marché de Noël', 'lieu' => 'Amiens Centre', 'effectif' => '8/8', 'status' => 'ok'],
    ['date' => '18/12', 'nom' => 'Concert Zénith', 'lieu' => 'Zénith Amiens', 'effectif' => '10/12', 'status' => 'info'],
];

$etat_materiel = [
    ['nom' => 'VPSP 01 (Master)', 'etat' => 'Opérationnel', 'color' => 'green'],
    ['nom' => 'VPSP 02 (Traffic)', 'etat' => 'Révision Freins', 'color' => 'red'],
    ['nom' => 'Zodiac MK4', 'etat' => 'Opérationnel', 'color' => 'green'],
    ['nom' => 'Radio VHF (Lot A)', 'etat' => 'Charge en cours', 'color' => 'yellow'],
];
?>

<!-- Contenu Principal du Dashboard -->
<div class="max-w-7xl mx-auto">
    
    <!-- Titre et Message de Bienvenue -->
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Tableau de Bord Opérationnel</h1>
            <p class="text-gray-500 mt-1">Bienvenue, <?php echo htmlspecialchars($userName ?? 'Utilisateur'); ?>. Voici la situation du CFI aujourd'hui.</p>
        </div>
        <div class="hidden md:block text-sm text-gray-400">
            Dernière mise à jour : <?php echo date('H:i'); ?>
        </div>
    </div>

    <!-- 1. Indicateurs Clés (KPI Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Carte Bénévoles -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-600 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Bénévoles Actifs</p>
                <p class="text-3xl font-bold text-gray-800 mt-1"><?php echo $stats['benevoles_actifs']; ?></p>
            </div>
            <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <!-- Carte DPS -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">DPS à Venir</p>
                <p class="text-3xl font-bold text-gray-800 mt-1"><?php echo $stats['dps_avenir']; ?></p>
            </div>
            <div class="p-3 bg-orange-50 rounded-full text-orange-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
        </div>

        <!-- Carte Formation -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Sessions Formation</p>
                <p class="text-3xl font-bold text-gray-800 mt-1"><?php echo $stats['formations_cours']; ?></p>
            </div>
            <div class="p-3 bg-green-50 rounded-full text-green-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
        </div>

        <!-- Carte Heures -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Heures Mission</p>
                <p class="text-3xl font-bold text-gray-800 mt-1"><?php echo $stats['heures_mission']; ?> h</p>
            </div>
            <div class="p-3 bg-purple-50 rounded-full text-purple-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- 2. Section Principale : Prochains DPS (Colonne Action retirée) -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-xl">
                <h2 class="text-lg font-bold text-gray-800">Prochains Dispositifs (DPS)</h2>
                <a href="DPS.php" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Voir tout →</a>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm whitespace-nowrap">
                        <thead class="uppercase tracking-wider border-b-2 border-gray-100 font-medium text-gray-500">
                            <tr>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Événement</th>
                                <th class="px-4 py-3">Lieu</th>
                                <th class="px-4 py-3">Effectif</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach($prochains_dps as $dps): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-medium text-gray-900"><?php echo $dps['date']; ?></td>
                                <td class="px-4 py-3 font-semibold text-gray-800"><?php echo $dps['nom']; ?></td>
                                <td class="px-4 py-3 text-gray-500"><?php echo $dps['lieu']; ?></td>
                                <td class="px-4 py-3">
                                    <?php if($dps['status'] == 'warning'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            ⚠️ <?php echo $dps['effectif']; ?>
                                        </span>
                                    <?php elseif($dps['status'] == 'ok'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✅ <?php echo $dps['effectif']; ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            ℹ️ <?php echo $dps['effectif']; ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 3. Colonne Latérale : État du Matériel (Actions Rapides retirées) -->
        <div class="space-y-8">
            
            <!-- État Matériel -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                    <h2 class="text-lg font-bold text-gray-800">État Opérationnel</h2>
                </div>
                <div class="p-4 space-y-3">
                    <?php foreach($etat_materiel as $mat): ?>
                    <div class="flex items-center justify-between p-2 rounded hover:bg-gray-50">
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full mr-3 bg-<?php echo $mat['color']; ?>-500"></span>
                            <span class="text-sm font-medium text-gray-700"><?php echo $mat['nom']; ?></span>
                        </div>
                        <span class="text-xs font-semibold text-gray-500"><?php echo $mat['etat']; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

    </div>
</div>

<?php 
// 3. Inclusion du pied de page
include __DIR__ . '/footer.php'; 
?>