<?php
// Fichier : dps_detail.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include __DIR__ . '/header.php'; 

$dps_id = $_GET['id'] ?? 1;

// Init session avec données par défaut si besoin
if (!isset($_SESSION['dps_list'])) { include 'api/dps_list.php'; }

// Récupération du DPS cible
$dps_info = $_SESSION['dps_list'][$dps_id] ?? $_SESSION['dps_list'][1];

// Sécurisation et peuplement des données équipes si vides
if (empty($dps_info['teams'])) {
    $dps_info['teams'] = [
        ['id' => 't1', 'name' => 'PC Sécurité', 'type' => 'PC', 'capacity' => 2, 'members' => ['H. DUPONT']],
        ['id' => 't2', 'name' => 'VPSP 01', 'type' => 'VPSP', 'capacity' => 4, 'members' => []],
        ['id' => 't3', 'name' => 'Binôme Alpha', 'type' => 'PAP', 'capacity' => 2, 'members' => []]
    ];
    $_SESSION['dps_list'][$dps_id]['teams'] = $dps_info['teams'];
}

$dps_info['chef'] = $dps_info['chef'] ?? 'Non assigné';
$dps_info['canal'] = $dps_info['canal'] ?? '01';
$dps_info['statut'] = $dps_info['statut'] ?? 'non_prepare';
$dps_info['inventory_status'] = $dps_info['inventory_status'] ?? 'pending';

$user_name = "Vous"; 

// Logique Statut
$status_label = "Inconnu"; $status_color = "text-gray-500 bg-gray-100"; $status_dot = "bg-gray-500";
switch($dps_info['statut']) {
    case 'en_cours': $status_label = "En Cours"; $status_color = "text-green-700 bg-green-100"; $status_dot = "bg-green-500"; break;
    case 'prepare': $status_label = "Ouvert / Prêt"; $status_color = "text-blue-700 bg-blue-100"; $status_dot = "bg-blue-500"; break;
    case 'non_prepare': $status_label = "À Planifier"; $status_color = "text-orange-700 bg-orange-100"; $status_dot = "bg-orange-500"; break;
    case 'termine': $status_label = "Terminé"; $status_color = "text-gray-700 bg-gray-200"; $status_dot = "bg-gray-500"; break;
}

// Données Inventaire (Mock)
$inventaire = [
    'vehicules' => [
        [
            'id' => 'V01', 'nom' => 'VPSP 01 (Master)',
            'check_vehicule' => ['Niveaux (Huile, LDR)' => true, 'Pression Pneus' => true, 'Feux & Sirène' => true, 'Radio Bord' => false, 'Chauffage Cellule' => false],
            'sacs' => [
                'Sac PS (Lot A)' => ['Oxygène (>100b)' => true, 'BAVU' => true, 'Aspirateur' => true, 'Colliers Cervicaux' => true, 'Pansements' => false],
                'Sac DSA' => ['Défibrillateur' => true, 'Electrodes Adulte' => true, 'Electrodes Enfant' => true, 'Rasoir' => true]
            ]
        ]
    ]
];

$inv_locked = ($dps_info['inventory_status'] === 'validated');
$inv_disabled_attr = $inv_locked ? 'disabled' : '';
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 relative">

    <!-- Header Mission -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <div class="flex items-center text-sm text-gray-500 mb-1">
                <a href="DPS.php" class="hover:text-blue-600 transition flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Retour liste
                </a>
                <span class="mx-2 text-gray-300">|</span>
                <span class="flex items-center px-2 py-0.5 rounded text-xs font-bold uppercase <?php echo $status_color; ?>">
                    <span class="w-2 h-2 rounded-full <?php echo $status_dot; ?> mr-2 <?php echo ($dps_info['statut'] === 'en_cours') ? 'animate-pulse' : ''; ?>"></span>
                    <?php echo $status_label; ?>
                </span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900"><?php echo htmlspecialchars($dps_info['title']); ?></h1>
            <p class="text-gray-500 text-sm mt-1">
                Lieu : <strong><?php echo htmlspecialchars($dps_info['lieu']); ?></strong> | 
                Chef : <strong><?php echo htmlspecialchars($dps_info['chef']); ?></strong> | 
                Canal : <strong><?php echo htmlspecialchars($dps_info['canal']); ?></strong>
            </p>
        </div>
        
        <div class="flex gap-2">
            <?php if($dps_info['statut'] === 'en_cours'): ?>
                <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-bold shadow flex items-center animate-pulse">Alerte Renfort</button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Navigation Onglets -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <button onclick="switchMainTab('dashboard')" id="tab-btn-dashboard" class="border-b-2 border-blue-500 text-blue-600 py-4 px-1 font-medium text-sm">Tableau de Bord</button>
            <button onclick="switchMainTab('teams')" id="tab-btn-teams" class="border-b-2 border-transparent text-gray-500 hover:text-gray-700 py-4 px-1 font-medium text-sm">
                Équipes & Inscriptions <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 rounded-full ml-1"><?php echo count($dps_info['teams']); ?></span>
            </button>
            <button onclick="switchMainTab('inventaire')" id="tab-btn-inventaire" class="border-b-2 border-transparent text-gray-500 hover:text-gray-700 py-4 px-1 font-medium text-sm">
                Inventaire <?php if($inv_locked): ?><span class="text-green-600 ml-1">✓</span><?php endif; ?>
            </button>
        </nav>
    </div>

    <!-- === VUE 1 : DASHBOARD === -->
    <div id="view-dashboard" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Carte -->
            <div class="bg-white rounded-xl shadow border overflow-hidden">
                <div class="p-3 bg-gray-50 border-b flex justify-between items-center">
                    <h2 class="font-bold text-gray-800">Situation Terrain</h2>
                    <button onclick="locateUser()" class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded font-bold">Ma position</button>
                </div>
                <!-- z-0 sur la map, important -->
                <div id="live-map" class="h-80 w-full bg-gray-100 z-0 relative"></div>
            </div>
            
            <!-- Main Courante -->
            <div class="bg-white rounded-xl shadow border overflow-hidden">
                <div class="p-3 bg-gray-50 border-b flex justify-between items-center">
                    <h2 class="font-bold text-gray-800">Main Courante</h2>
                    <button onclick="openVictimModal()" class="text-xs bg-gray-800 text-white px-3 py-1.5 rounded font-bold">+ Bilan</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 font-medium">
                            <tr><th class="px-4 py-2">Heure</th><th class="px-4 py-2">Victime</th><th class="px-4 py-2">Motif</th><th class="px-4 py-2">Gravité</th><th class="px-4 py-2">Action</th></tr>
                        </thead>
                        <tbody id="victims-table-body" class="divide-y divide-gray-100">
                            <!-- JS Inject -->
                        </tbody>
                    </table>
                    <div id="empty-state" class="p-8 text-center text-gray-400 italic">Aucune victime pour le moment.</div>
                </div>
            </div>
        </div>

        <!-- Colonne Droite Dashboard -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow border overflow-hidden">
                <div class="p-3 bg-gray-50 border-b"><h2 class="font-bold">Mon Affectation</h2></div>
                <div class="p-4">
                    <?php 
                    $my_team = null;
                    if (!empty($dps_info['teams'])) {
                        foreach($dps_info['teams'] as $t) { if(in_array($user_name, $t['members'])) { $my_team = $t; break; } }
                    }
                    ?>
                    <?php if($my_team): ?>
                        <div class="bg-green-50 border border-green-200 rounded p-3 text-center">
                            <p class="text-green-800 font-bold text-lg">✅ Inscrit</p>
                            <p class="text-green-700">Équipe : <strong><?php echo $my_team['name']; ?></strong></p>
                        </div>
                    <?php else: ?>
                        <div class="bg-yellow-50 border border-yellow-200 rounded p-3 text-center">
                            <p class="text-yellow-800 font-bold">⚠️ Non affecté</p>
                            <button onclick="switchMainTab('teams')" class="bg-blue-600 text-white px-3 py-1 rounded text-sm mt-2">S'inscrire</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- === VUE 2 : ÉQUIPES & INSCRIPTIONS === -->
    <div id="view-teams" class="hidden">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Gestion des Effectifs</h2>
        <?php if(empty($dps_info['teams'])): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded">Aucune équipe configurée pour ce DPS.</div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($dps_info['teams'] as $team): 
                $count = count($team['members']);
                $capacity = $team['capacity']; 
                $full = $count >= $capacity;
                $member = in_array($user_name, $team['members']);
                $border = $member ? "border-green-500 ring-2 ring-green-100" : ($full ? "border-gray-200 opacity-75" : "border-blue-200");
                $progress_color = $full ? "bg-red-500" : "bg-blue-500";
            ?>
            <div class="bg-white rounded-xl shadow border <?php echo $border; ?> overflow-hidden flex flex-col">
                <div class="px-4 py-3 bg-gray-50 border-b flex justify-between">
                    <h3 class="font-bold"><?php echo $team['name']; ?></h3>
                    <span class="text-xs bg-white border px-2 py-1 rounded"><?php echo $team['type']; ?></span>
                </div>
                <div class="p-4 flex-grow space-y-3">
                    <div class="flex justify-between text-xs">
                        <span>Places</span>
                        <span class="font-bold <?php echo $full?'text-red-600':'text-blue-600'; ?>"><?php echo "$count / {$capacity}"; ?></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2"><div class="<?php echo $progress_color; ?> h-2 rounded-full" style="width: <?php echo ($count/$capacity)*100; ?>%"></div></div>
                    
                    <!-- Liste des Membres & Postes Vacants -->
                    <ul class="text-sm space-y-2">
                        <?php 
                        // Boucle sur la capacité totale
                        for($i=0; $i < $capacity; $i++): 
                            $role_name = ($i === 0) ? "Chef d'agrès" : "Équipier";
                            $is_vacant = !isset($team['members'][$i]);
                        ?>
                            <li class="flex items-center justify-between p-1 rounded <?php echo $is_vacant ? 'border border-dashed border-gray-300 bg-gray-50' : ''; ?>">
                                <?php if (!$is_vacant): 
                                    $m = $team['members'][$i];
                                    $is_me = ($m === $user_name);
                                ?>
                                    <div class="flex items-center">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        <span class="<?php echo $is_me ? 'font-bold text-blue-700' : 'text-gray-800'; ?>">
                                            <?php echo $m; ?>
                                        </span>
                                    </div>
                                    <span class="text-[10px] uppercase text-gray-500 bg-gray-200 px-1 rounded"><?php echo $role_name; ?></span>
                                <?php else: ?>
                                    <div class="flex items-center text-gray-400 italic">
                                        <span class="w-2 h-2 border border-gray-400 rounded-full mr-2"></span>
                                        Vacant
                                    </div>
                                    <span class="text-[10px] uppercase text-gray-400 border border-gray-300 px-1 rounded"><?php echo $role_name; ?></span>
                                <?php endif; ?>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </div>
                <div class="p-3 bg-gray-50 border-t text-center">
                    <?php if ($member): ?>
                        <button onclick="toggleSubscription('<?php echo $team['id']; ?>', 'leave')" class="text-red-600 text-sm font-bold border border-red-200 bg-white px-4 py-2 rounded hover:bg-red-50 w-full">Se désinscrire</button>
                    <?php elseif (!$full): ?>
                        <button onclick="toggleSubscription('<?php echo $team['id']; ?>', 'join')" class="bg-blue-600 text-white text-sm font-bold px-4 py-2 rounded hover:bg-blue-700 w-full">Rejoindre</button>
                    <?php else: ?>
                        <button disabled class="bg-gray-100 text-gray-400 text-sm font-bold px-4 py-2 rounded w-full cursor-not-allowed">Complet</button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- === VUE 3 : INVENTAIRE INTERACTIF === -->
    <div id="view-inventaire" class="hidden space-y-6">
        <?php if($inv_locked): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                <p class="font-bold">Inventaire Validé et Verrouillé</p>
                <p class="text-sm">Le matériel a été contrôlé. Aucune modification n'est possible.</p>
            </div>
        <?php else: ?>
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4"><p class="text-sm text-blue-700">Cochez les éléments présents. Les lignes non cochées (orange) seront signalées lors de la validation.</p></div>
        <?php endif; ?>
        
        <?php foreach($inventaire['vehicules'] as $v): ?>
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b font-bold text-lg">🚑 <?php echo $v['nom']; ?></div>
            <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <h4 class="font-bold text-gray-700 mb-2 border-b pb-1">Véhicule</h4>
                    <ul class="space-y-2">
                        <?php foreach($v['check_vehicule'] as $item => $state): $cls = $state ? "hover:bg-gray-50" : "bg-orange-50 border-orange-200 text-orange-800 font-medium"; ?>
                        <li class="inv-item flex items-center p-2 rounded border <?php echo $cls; ?>">
                            <label class="flex items-center w-full cursor-pointer">
                                <input type="checkbox" <?php echo $state?'checked':''; ?> <?php echo $inv_disabled_attr; ?> class="form-checkbox h-5 w-5 text-blue-600 rounded disabled:opacity-50" onchange="updateCheckStyle(this)">
                                <span class="ml-3 text-sm item-name"><?php echo $item; ?></span>
                            </label>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-gray-700 mb-2 border-b pb-1">Sanitaire</h4>
                    <?php foreach($v['sacs'] as $sac => $cont): ?>
                        <h5 class="text-sm font-bold text-blue-800 mt-3 mb-1"><?php echo $sac; ?></h5>
                        <ul class="space-y-1">
                            <?php foreach($cont as $item => $state): $cls = $state ? "hover:bg-gray-50" : "bg-orange-50 border-orange-200 text-orange-800 font-medium"; ?>
                            <li class="inv-item flex items-center p-2 rounded border <?php echo $cls; ?>">
                                <label class="flex items-center w-full cursor-pointer">
                                    <input type="checkbox" <?php echo $state?'checked':''; ?> <?php echo $inv_disabled_attr; ?> class="form-checkbox h-4 w-4 text-green-600 rounded disabled:opacity-50" onchange="updateCheckStyle(this)">
                                    <span class="ml-2 text-sm item-name"><?php echo $item; ?></span>
                                </label>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if(!$inv_locked): ?>
        <div class="flex justify-end pt-4 pb-8">
            <button onclick="validateInventory()" class="bg-green-600 text-white px-6 py-3 rounded-lg font-bold shadow hover:bg-green-700 transition">Valider l'Inventaire</button>
        </div>
        <?php endif; ?>
    </div>

</div>

<!-- MODALE MANQUANTS -->
<div id="missing-items-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden z-[9999] flex items-center justify-center">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-2xl p-6 border-t-4 border-orange-500 relative">
        <h3 class="text-xl font-bold text-gray-900 mb-4">⚠️ Attention : Éléments non cochés</h3>
        <div class="bg-orange-50 rounded-lg p-4 mb-4 max-h-60 overflow-y-auto border border-orange-100">
            <ul id="missing-items-list" class="list-disc list-inside text-sm text-orange-800 font-medium"></ul>
        </div>
        <div class="flex justify-end gap-3">
            <button onclick="document.getElementById('missing-items-modal').classList.add('hidden')" class="px-4 py-2 border rounded hover:bg-gray-50">Corriger</button>
            <button onclick="confirmValidation()" class="px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700 font-bold">Valider quand même</button>
        </div>
    </div>
</div>

<!-- === MODALE DE FICHE BILAN COMPLÈTE (OFFICIEL SNSM 2023) === -->
<div id="victim-modal" class="fixed inset-0 bg-gray-900 bg-opacity-70 hidden z-[9999] overflow-hidden h-full w-full flex items-center justify-center">
    <div class="relative bg-white w-full max-w-5xl h-[90vh] rounded-xl shadow-2xl flex flex-col">
        
        <!-- Header Modale -->
        <div class="flex justify-between items-center bg-blue-900 text-white px-6 py-4 rounded-t-xl shrink-0">
            <div>
                <h3 class="text-xl font-bold flex items-center gap-2">
                    <img src="https://placehold.co/30x30/FBBF24/1E40AF?text=S" class="rounded-full border border-white"> 
                    Fiche Bilan SNSM
                </h3>
                <p class="text-xs opacity-80 text-blue-200">Version Avr 2023 - DPS</p>
            </div>
            <button onclick="closeVictimModal()" class="text-white hover:text-gray-300 bg-blue-800 p-2 rounded-full hover:bg-blue-700 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Navigation Onglets -->
        <div class="flex border-b border-gray-200 bg-gray-50 overflow-x-auto shrink-0">
            <button onclick="switchModalTab('admin')" class="modal-tab-btn px-4 py-3 text-sm font-medium border-b-2 border-blue-700 text-blue-700 focus:outline-none" id="mtab-admin">1. Admin & Contexte</button>
            <button onclick="switchModalTab('bilan2')" class="modal-tab-btn px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-blue-700 focus:outline-none" id="mtab-bilan2">2. Bilans 2-3</button>
            <button onclick="switchModalTab('bilan4')" class="modal-tab-btn px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-blue-700 focus:outline-none" id="mtab-bilan4">3. Bilan 4 & Interro</button>
            <button onclick="switchModalTab('exam')" class="modal-tab-btn px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-blue-700 focus:outline-none" id="mtab-exam">4. Examen</button>
            <button onclick="switchModalTab('spe')" class="modal-tab-btn px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-blue-700 focus:outline-none" id="mtab-spe">5. Spécificités</button>
            <button onclick="switchModalTab('gestes')" class="modal-tab-btn px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-blue-700 focus:outline-none" id="mtab-gestes">6. Gestes</button>
            <button onclick="switchModalTab('trans')" class="modal-tab-btn px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-blue-700 focus:outline-none" id="mtab-trans">7. Trans. & Clôture</button>
        </div>

        <!-- Corps du Formulaire (Scrollable) -->
        <form id="bilan-form" onsubmit="saveVictim(event)" class="flex-grow overflow-y-auto p-6 bg-gray-50 text-sm">
            <!-- Champ Caché pour ID Bilan (Modif) -->
            <input type="hidden" name="v_id" id="v_id" value="">

            <!-- ONGLET 1: Page 1 (Admin & Contexte) -->
            <div id="mview-admin" class="tab-content space-y-4">
                <div class="bg-white p-4 rounded border">
                    <h4 class="font-bold border-b pb-1 mb-2 text-blue-800">ADMINISTRATIF VICTIME</h4>
                    <div class="grid grid-cols-2 gap-2">
                        <input name="v_nom" placeholder="Nom" class="border p-1 rounded w-full" required>
                        <input name="v_prenom" placeholder="Prénom" class="border p-1 rounded w-full">
                        <div class="flex gap-2">
                            <input type="date" name="v_ddn" class="border p-1 rounded w-2/3 text-xs">
                            <input type="number" name="v_age" placeholder="Age" class="border p-1 rounded w-1/3">
                        </div>
                        <select name="v_sexe" class="border p-1 rounded w-full"><option>M</option><option>F</option></select>
                    </div>
                    <input name="v_adresse" placeholder="Adresse" class="border p-1 rounded w-full mt-2">
                    <div class="grid grid-cols-2 gap-2 mt-2">
                        <input name="v_tel" placeholder="Tél" class="border p-1 rounded w-full">
                        <input name="v_tel_famille" placeholder="Tél Famille" class="border p-1 rounded w-full">
                    </div>
                </div>
                <div class="bg-white p-4 rounded border">
                    <h4 class="font-bold border-b pb-1 mb-2 text-blue-800">PREMIER BILAN</h4>
                    <div class="grid grid-cols-2 gap-2">
                        <input name="v_nature" placeholder="Nature Inter." class="border p-1 rounded w-full">
                        <div class="flex gap-1"><input type="date" value="<?php echo date('Y-m-d'); ?>" class="border p-1 rounded w-1/2 text-xs"><input type="time" name="v_heure" value="<?php echo date('H:i'); ?>" class="border p-1 rounded w-1/2"></div>
                    </div>
                    <label class="flex items-center mt-2"><input type="checkbox" class="mr-1"> Risque écarté</label>
                </div>
            </div>

            <!-- ONGLET 2: Bilans 2 & 3 (Page 2 Haut/Milieu) -->
            <div id="mview-bilan2" class="tab-content hidden space-y-4">
                <div class="bg-red-50 p-4 rounded border border-red-200">
                    <h4 class="font-bold text-red-800 mb-2">DEUXIÈME BILAN (Détresses Vitales)</h4>
                    <div class="grid grid-cols-2 gap-2">
                        <label><input type="checkbox" class="mr-1 text-red-600"> Hémorragie Externe</label>
                        <label><input type="checkbox" class="mr-1 text-red-600"> Obstruction VA</label>
                        <label><input type="checkbox" class="mr-1 text-red-600"> Perte Connaissance</label>
                        <label><input type="checkbox" class="mr-1 text-red-600"> Arrêt Cardio-Resp.</label>
                    </div>
                </div>
                <div class="bg-white p-4 rounded border">
                    <h4 class="font-bold text-blue-800 mb-2">TROISIÈME BILAN (Fonctions)</h4>
                    <div class="grid grid-cols-3 gap-4 text-xs">
                        <div>
                            <strong class="block mb-1">Respiratoire</strong>
                            <select class="w-full border mb-1"><option>Parle Facilement</option><option>Difficilement</option><option>Non</option></select>
                            <select class="w-full border mb-1"><option>Respire Facilement</option><option>Gêne</option></select>
                            <select class="w-full border"><option>Freq. Normale</option><option>Rapide</option><option>Lente</option></select>
                        </div>
                        <div>
                            <strong class="block mb-1">Circulatoire</strong>
                            <select class="w-full border mb-1"><option>Pouls Radial</option><option>Carotidien</option><option>Absent</option></select>
                            <select class="w-full border mb-1"><option>Peau Chaude</option><option>Froide</option><option>Pâle</option></select>
                            <select class="w-full border"><option>TRC < 2s</option><option>TRC > 2s</option></select>
                        </div>
                        <div>
                            <strong class="block mb-1">Neuro</strong>
                            <select class="w-full border mb-1"><option>Orientée</option><option>Désorientée</option><option>Agitée</option></select>
                            <label><input type="checkbox"> PC Passagère</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ONGLET 3: Bilan 4 & Interro (Page 2 Bas) -->
            <div id="mview-bilan4" class="tab-content hidden space-y-4">
                <div class="bg-gray-100 p-4 rounded border">
                    <h4 class="font-bold text-gray-800 mb-2">QUATRIÈME BILAN (Chiffré)</h4>
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div><label class="text-xs block">FR (mvt/min)</label><input type="number" name="v_fr" class="w-full text-center border-gray-300 rounded p-1"></div>
                        <div><label class="text-xs block">SpO2 (%)</label><input type="number" name="v_sat" class="w-full text-center border-gray-300 rounded p-1"></div>
                        <div><label class="text-xs block">FC (btm/min)</label><input type="number" name="v_pouls" class="w-full text-center border-gray-300 rounded p-1"></div>
                        <div><label class="text-xs block">TA (mmHg)</label><input type="text" name="v_ta" class="w-full text-center border-gray-300 rounded p-1" placeholder="12/8"></div>
                        <div><label class="text-xs block">Glyc (g/L)</label><input type="text" class="border w-full text-center p-1 rounded"></div>
                        <div><label class="text-xs block">Temp (°C)</label><input type="text" class="border w-full text-center p-1 rounded"></div>
                    </div>
                    <div class="mt-2 text-xs flex justify-between">
                        <div>Score EVDA: <select class="border"><option>E</option><option>V</option><option>D</option><option>A</option></select></div>
                        <div>Score AVC: <label><input type="checkbox"> Visage</label> <label><input type="checkbox"> Bras</label> <label><input type="checkbox"> Parole</label></div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded border">
                    <h4 class="font-bold text-gray-800 mb-2">INTERROGATOIRE</h4>
                    <textarea name="v_motif" placeholder="Plainte principale / PQRST" class="w-full border p-1 rounded mb-2" required></textarea>
                    <div class="grid grid-cols-2 gap-2">
                        <input placeholder="Maladie / Hospit." class="w-full border p-1 rounded">
                        <input placeholder="Traitements" class="w-full border p-1 rounded">
                        <input placeholder="Allergies" class="w-full border p-1 rounded">
                        <div class="flex items-center text-xs">EVS (0-10): <input type="number" class="border w-10 ml-1"></div>
                    </div>
                </div>
            </div>

            <!-- ONGLET 4: EXAMEN (Page 2 Schéma) -->
            <div id="mview-exam" class="tab-content hidden space-y-4">
                <div class="bg-white p-4 rounded border">
                    <h4 class="font-bold text-gray-800 mb-2">EXAMEN PHYSIQUE</h4>
                    <div class="flex gap-4">
                        <div class="w-1/3 bg-gray-200 h-40 flex items-center justify-center text-gray-500 text-xs text-center border rounded">
                            [Schéma Corporel]<br>Face / Dos
                        </div>
                        <div class="w-2/3 text-xs">
                            <p class="mb-1 text-gray-500">P:Plaie B:Brûlure H:Hémorragie T:Trauma S:Section G:Gonflement</p>
                            <textarea class="w-full h-32 border p-2 rounded" placeholder="Localisation et description des lésions..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ONGLET 5: SPÉCIFICITÉS (Page 3 Haut) -->
            <div id="mview-spe" class="tab-content hidden space-y-4">
                <div class="bg-blue-50 p-4 rounded border border-blue-200">
                    <h4 class="font-bold text-blue-900 mb-2">SPÉCIFICITÉS MARITIMES</h4>
                    <div class="grid grid-cols-2 gap-4 text-xs">
                        <div>
                            <strong class="block mb-1">Noyade (Stade)</strong>
                            <label><input type="radio" name="n"> 1 (Stress)</label><br>
                            <label><input type="radio" name="n"> 2 (P. Hypox)</label><br>
                            <label><input type="radio" name="n"> 3 (G. Hypox)</label><br>
                            <label><input type="radio" name="n"> 4 (Anoxique)</label>
                        </div>
                        <div>
                            <strong class="block mb-1">Hypothermie</strong>
                            <label><input type="radio" name="h"> Légère (35-32)</label><br>
                            <label><input type="radio" name="h"> Modérée (32-28)</label><br>
                            <label><input type="radio" name="h"> Sévère (< 28)</label>
                        </div>
                    </div>
                    <div class="mt-2 pt-2 border-t border-blue-200">
                        <strong class="block mb-1 text-xs">Accident Plongée</strong>
                        <div class="grid grid-cols-3 gap-1">
                            <input placeholder="Prof. (m)" class="border p-1 rounded text-xs">
                            <input placeholder="Durée (min)" class="border p-1 rounded text-xs">
                            <input placeholder="Heure sortie" class="border p-1 rounded text-xs">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ONGLET 6: GESTES (Page 3 Bas) -->
            <div id="mview-gestes" class="tab-content hidden space-y-4">
                <div class="bg-white p-4 rounded border">
                    <h4 class="font-bold text-gray-800 mb-2">GESTES EFFECTUÉS</h4>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <label><input type="checkbox" class="mr-1"> LVA / Aspiration</label>
                        <label><input type="checkbox" class="mr-1"> O2 Inhal (<input class="w-6 border text-center" placeholder="?"> L)</label>
                        <label><input type="checkbox" class="mr-1"> O2 Insuffl. (BAVU)</label>
                        <label><input type="checkbox" class="mr-1"> RCP / Massage</label>
                        <label><input type="checkbox" class="mr-1"> DSA (<input class="w-6 border text-center" placeholder="0"> chocs)</label>
                        <label><input type="checkbox" class="mr-1"> Collier Cervical</label>
                        <label><input type="checkbox" class="mr-1"> Pansement Comp.</label>
                        <label class="bg-red-50 p-1 rounded"><input type="checkbox" class="mr-1"> Garrot (H: <input type="time" class="border w-16"> )</label>
                        <label><input type="checkbox" class="mr-1"> PLS</label>
                        <label><input type="checkbox" class="mr-1"> Réchauffement</label>
                    </div>
                </div>
                <div class="bg-white p-4 rounded border">
                    <h4 class="font-bold text-gray-800 mb-2">SURVEILLANCE</h4>
                    <table class="w-full text-xs text-center border">
                        <thead class="bg-gray-100"><tr><th>H</th><th>FR</th><th>SpO2</th><th>FC</th><th>TA</th><th>Consc.</th></tr></thead>
                        <tbody>
                            <tr><td><input class="w-full text-center border-none"></td><td><input class="w-full text-center border-none"></td><td><input class="w-full text-center border-none"></td><td><input class="w-full text-center border-none"></td><td><input class="w-full text-center border-none"></td><td><input class="w-full text-center border-none"></td></tr>
                            <tr><td><input class="w-full text-center border-none"></td><td><input class="w-full text-center border-none"></td><td><input class="w-full text-center border-none"></td><td><input class="w-full text-center border-none"></td><td><input class="w-full text-center border-none"></td><td><input class="w-full text-center border-none"></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ONGLET 7: TRANSMISSION & CLÔTURE (Page 4) -->
            <div id="mview-trans" class="tab-content hidden space-y-4">
                <!-- Transmission Rapide (QR Code) -->
                <div class="bg-blue-900 text-white p-4 rounded shadow">
                    <h4 class="font-bold mb-2 flex items-center"><span class="text-xl mr-2">📱</span> Transmission Numérique</h4>
                    <div class="flex gap-4 items-center">
                        <div class="bg-white p-2 rounded"><img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=https://crm-intrasomme.fr/bilan/v-<?php echo uniqid(); ?>" class="w-20 h-20"></div>
                        <div class="w-full">
                            <label class="text-xs uppercase text-blue-300">Lien Coordination / Pompier</label>
                            <input readonly value="https://crm-intrasomme.fr/bilan/v-<?php echo uniqid(); ?>" class="w-full text-xs text-black p-1 rounded font-mono mt-1">
                            <p class="text-[10px] text-blue-200 mt-1">Scanner pour récupérer le bilan complet.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded border">
                    <h4 class="font-bold text-gray-800 mb-2">CLÔTURE INTERVENTION</h4>
                    <div class="grid grid-cols-2 gap-4 text-xs mb-2">
                        <div>
                            <strong>Bilan passé au:</strong><br>
                            <label><input type="radio" name="dest" value="15"> SAMU 15</label> <label><input type="radio" name="dest" value="18"> CODIS 18</label>
                        </div>
                        <div>
                            <strong>Gravité:</strong><br>
                            <select name="v_gravite" class="border rounded p-1 w-full font-bold"><option value="VL" class="text-green-600">VL (Vert)</option><option value="UR" class="text-yellow-600">UR (Jaune)</option><option value="UA" class="text-red-600">UA (Rouge)</option><option value="DCD">DCD (Noir)</option></select>
                        </div>
                    </div>
                    <div class="mb-2">
                        <strong>Devenir:</strong>
                        <select name="v_orientation" class="border rounded p-1 w-full text-xs"><option>Laissé sur place</option><option>Evac. CHU</option><option>Refus de Soin</option></select>
                    </div>
                    <div class="border border-red-200 bg-red-50 p-2 rounded text-xs">
                        <label class="flex items-start"><input type="checkbox" class="mt-1 mr-2 text-red-600"> <strong>Certificat de Refus de Soins signé (Papier) ?</strong></label>
                    </div>
                </div>
            </div>

            <!-- Footer Modale avec Bouton SUBMIT LIE AU FORM -->
            <div class="mt-6 flex justify-end gap-3 pt-4 border-t sticky bottom-0 bg-gray-50 z-20">
                <button type="button" onclick="closeVictimModal()" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 font-medium">Fermer</button>
                <!-- ATTENTION : Bouton Submit lié au formulaire par l'ID ou placé DANS le form (ici dans le form) -->
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold shadow-lg flex items-center">
                    Enregistrer la Fiche
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // --- CARTE ---
    const mapLive = L.map('live-map').setView([<?php echo $dps_info['lat']; ?>, <?php echo $dps_info['lng']; ?>], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapLive);
    L.marker([<?php echo $dps_info['lat']; ?>, <?php echo $dps_info['lng']; ?>]).addTo(mapLive).bindPopup("PC Sécurité").openPopup();

    function locateUser() {
        if(!navigator.geolocation) return alert("Pas de GPS");
        navigator.geolocation.getCurrentPosition(pos => {
            L.marker([pos.coords.latitude, pos.coords.longitude]).addTo(mapLive).bindPopup("Vous").openPopup();
            mapLive.setView([pos.coords.latitude, pos.coords.longitude], 16);
        });
    }

    // --- TABS PAGE ---
    function switchMainTab(tab) {
        ['dashboard', 'teams', 'inventaire'].forEach(t => {
            document.getElementById('view-'+t).classList.add('hidden');
            document.getElementById('view-'+t).classList.remove('grid');
            document.getElementById('tab-btn-'+t).className = "border-b-2 border-transparent text-gray-500 hover:text-gray-700 py-4 px-1 font-medium text-sm";
        });
        const view = document.getElementById('view-'+tab);
        view.classList.remove('hidden');
        if(tab === 'dashboard') {
            view.classList.add('grid');
            setTimeout(() => mapLive.invalidateSize(), 200);
        }
        document.getElementById('tab-btn-'+tab).className = "border-b-2 border-blue-500 text-blue-600 py-4 px-1 font-medium text-sm";
    }

    // --- TABS MODALE ---
    function switchModalTab(tab) {
        ['admin', 'bilan2', 'bilan4', 'exam', 'spe', 'gestes', 'trans'].forEach(t => {
            const el = document.getElementById('mview-'+t);
            if(el) el.classList.add('hidden');
            const btn = document.getElementById('mtab-'+t);
            if(btn) btn.className = "modal-tab-btn px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-blue-700 focus:outline-none";
        });
        document.getElementById('mview-'+tab).classList.remove('hidden');
        document.getElementById('mtab-'+tab).className = "modal-tab-btn px-4 py-3 text-sm font-medium border-b-2 border-blue-700 text-blue-700 focus:outline-none";
    }

    // --- INSCRIPTION ---
    function toggleSubscription(teamId, action) {
        fetch('api/subscribe_dps.php', {
            method: 'POST', headers: {'Content-Type':'application/json'},
            body: JSON.stringify({ dps_id: <?php echo $dps_id; ?>, team_id: teamId, action: action })
        }).then(r=>r.json()).then(d => {
            if(d.success) window.location.reload();
            else alert(d.message);
        });
    }

    // --- INVENTAIRE ---
    function updateCheckStyle(cb) {
        const row = cb.closest('.inv-item');
        if(cb.checked) { row.classList.remove('bg-orange-50','border-orange-200','text-orange-800'); row.classList.add('border-transparent'); }
        else { row.classList.add('bg-orange-50','border-orange-200','text-orange-800'); row.classList.remove('border-transparent'); }
    }
    function validateInventory() {
        const unchecked = [];
        document.querySelectorAll('#view-inventaire input[type="checkbox"]').forEach(cb => {
            if(!cb.checked) unchecked.push(cb.closest('label').querySelector('.item-name').textContent);
        });
        if(unchecked.length > 0) {
            document.getElementById('missing-items-list').innerHTML = unchecked.map(i => `<li>${i}</li>`).join('');
            document.getElementById('missing-items-modal').classList.remove('hidden');
        } else confirmValidation();
    }
    function confirmValidation() {
        alert("Inventaire Validé !");
        document.getElementById('missing-items-modal').classList.add('hidden');
        switchMainTab('dashboard');
    }

    // --- VICTIMES ---
    function openVictimModal() { document.getElementById('victim-modal').classList.remove('hidden'); switchModalTab('admin'); }
    function closeVictimModal() { document.getElementById('victim-modal').classList.add('hidden'); }
    
    function saveVictim(e) {
        e.preventDefault();
        const fd = new FormData(e.target);
        
        let color = 'bg-gray-500';
        if(fd.get('v_gravite')=='UA') color='bg-red-600';
        if(fd.get('v_gravite')=='UR') color='bg-yellow-500';
        if(fd.get('v_gravite')=='VL') color='bg-green-500';
        
        const tr = document.createElement('tr');
        tr.className = "hover:bg-gray-50 border-b";
        tr.innerHTML = `<td class="px-4 py-2">${fd.get('v_heure')}</td><td class="px-4 py-2 font-bold">${fd.get('v_nom')}</td><td class="px-4 py-2">${fd.get('v_motif')}</td><td class="px-4 py-2"><span class="${color} text-white px-2 py-0.5 rounded text-xs">${fd.get('v_gravite')}</span></td><td class="px-4 py-2"><button onclick="openVictimModal()" class="text-blue-600 text-xs font-bold hover:underline">Modifier</button></td>`;
        
        document.getElementById('victims-table-body').prepend(tr);
        document.getElementById('empty-state').style.display = 'none';
        closeVictimModal(); 
        // e.target.reset(); // On ne reset pas pour simuler l'édition si on rouvre
    }
</script>

<?php 
if (file_exists(__DIR__ . '/footer_simple.php')) { include __DIR__ . '/footer_simple.php'; } else { echo "</body></html>"; }
?>