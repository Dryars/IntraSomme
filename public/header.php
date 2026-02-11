<?php


// Fichier : header.php

// Assurons-nous que la session est démarrée pour stocker l'état des notifications

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


$currentPage = basename($_SERVER['PHP_SELF']);

// --- 1. CONFIGURATION DU CHEMIN RACINE (CRUCIAL) ---
// Ceci définit le dossier de base de votre site web sur le serveur.
$root = '/INTRASOMME/public';

// --- SIMULATION DES DONNÉES UTILISATEUR ---
$userName = "Hadrien"; 
$userId = "00876250";

// --- GESTION DES NOTIFICATIONS ---

// 1. Initialisation des notifications en session si elles n'existent pas encore
if (!isset($_SESSION['notifications'])) {
    $_SESSION['notifications'] = [
        [
            'id' => 1, // ID unique pour identifier la notif
            'category' => 'dps', 
            'text' => 'Rappel : DPS Marché de Noël demain', 
            'time' => 'Il y a 2h'
        ],
        [
            'id' => 2,
            'category' => 'document', 
            'text' => 'Nouveau document : Fiche Sécurité', 
            'time' => 'Il y a 5h'
        ],
    ];
}

// 2. Traitement de la suppression (si on vient de cliquer sur une notif)
if (isset($_GET['ack_notif'])) {
    $idToDelete = $_GET['ack_notif'];
    // On parcourt la session pour supprimer la notification correspondante
    foreach ($_SESSION['notifications'] as $key => $notif) {
        if ($notif['id'] == $idToDelete) {
            unset($_SESSION['notifications'][$key]);
            break; // On arrête dès qu'on a trouvé et supprimé
        }
    }
    // (Optionnel) On pourrait rediriger ici pour nettoyer l'URL, 
    // mais pour ce prototype, laisser le paramètre ne gêne pas.
}

// 3. Récupération de la liste à jour pour l'affichage
$notifications = $_SESSION['notifications'];
$notifCount = count($notifications);

// --- 2. DÉFINITION DES LIENS DE NAVIGATION ---
$navItems = [
    'Accueil'   => $root . '/index.php',
    'Pôles'     => $root . '/poles.php',
    'Essentiel' => $root . '/essentiel.php',
    'DPS'       => $root . '/DPS.php',
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IntraSomme CRM - <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', str_replace('.php', '', $currentPage)))); ?></title>
    
    <!-- Intégration de Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Styles communs pour l'en-tête -->
    <style>
        .header-bg {
            background-color: #1E40AF; /* Bleu foncé */
        }
        .nav-link {
            transition: all 0.3s ease;
            position: relative;
        }
        .nav-link.active {
            font-weight: 700;
            color: #FBBF24; /* Jaune/Or pour l'actif */
        }
        .nav-link:hover:not(.active) {
            color: #93C5FD; /* Bleu clair au survol */
        }
    </style>
</head>
<body class="bg-gray-50">

<header class="header-bg text-white shadow-lg sticky top-0 z-10">
    <div class="container mx-auto flex justify-between items-center p-4">
        
        <!-- Logo/Titre Principal (non cliquable) -->
        <div class="text-xl font-extrabold tracking-tight flex items-center">
            <!-- Logo CFI : Utilisation de $root pour garantir le chargement de l'image -->
            <img 
                src="<?php echo $root; ?>/images/cfiSomme.jpg" 
                alt="Logo IntraSomme CFI" 
                class="w-12 h-12 mr-3 rounded-full border-2 border-white object-cover"
                onerror="this.onerror=null; this.src='https://placehold.co/48x48/FBBF24/1E40AF?text=CFI';"
            >
            IntraSomme
        </div>
        
        <!-- Navigation Principale -->
        <nav class="hidden md:flex space-x-6 text-sm">
            <?php foreach ($navItems as $name => $link): ?>
                <a 
                    href="<?php echo htmlspecialchars($link); ?>" 
                    class="nav-link <?php 
                        // On compare juste le nom du fichier (basename) pour garder l'état actif
                        echo (basename($link) === $currentPage) ? 'active' : 'text-gray-300'; 
                    ?>"
                >
                    <?php echo htmlspecialchars($name); ?>
                </a>
            <?php endforeach; ?>
        </nav>
        
        <!-- Infos Utilisateur et Notifications (à droite) -->
        <div class="flex items-center space-x-4">
            
            <!-- Zone Notification avec Dropdown -->
            <div class="relative" id="notif-container">
                <!-- Bouton Cloche -->
                <button 
                    onclick="toggleNotifications()"
                    class="relative text-gray-300 hover:text-white transition focus:outline-none"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.174 5.923 6 8.384 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1"></path></svg>
                    
                    <!-- Badge Notification -->
                    <?php if ($notifCount > 0): ?>
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                            <?php echo $notifCount; ?>
                        </span>
                    <?php endif; ?>
                </button>

                <!-- Dropdown Menu des Notifications -->
                <div id="notif-dropdown" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-md shadow-xl overflow-hidden z-50 text-gray-800 border border-gray-200 ring-1 ring-black ring-opacity-5">
                    <div class="py-2">
                        <?php if ($notifCount > 0): ?>
                            <div class="px-4 py-2 border-b border-gray-100 bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider flex justify-between items-center">
                                <span>Notifications récentes</span>
                                <span class="bg-blue-100 text-blue-800 py-0.5 px-2 rounded-full"><?php echo $notifCount; ?></span>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                <?php foreach($notifications as $notif): 
                                    // Détermine le lien en fonction de la catégorie
                                    $targetLink = '#';
                                    if (isset($notif['category'])) {
                                        switch ($notif['category']) {
                                            case 'dps':
                                                $targetLink = $root . '/DPS.php';
                                                break;
                                            case 'document':
                                                $targetLink = $root . '/essentiel.php';
                                                break;
                                            case 'formation':
                                                $targetLink = $root . '/pole_formation.php';
                                                break;
                                            default:
                                                $targetLink = '#';
                                        }
                                    }
                                    
                                    // AJOUT : On ajoute le paramètre ack_notif à l'URL pour gérer la suppression
                                    // On vérifie s'il y a déjà des paramètres '?' dans l'URL
                                    $separator = (strpos($targetLink, '?') !== false) ? '&' : '?';
                                    $finalLink = $targetLink . $separator . 'ack_notif=' . $notif['id'];
                                ?>
                                    <a href="<?php echo htmlspecialchars($finalLink); ?>" class="block px-4 py-3 hover:bg-blue-50 border-b border-gray-100 transition last:border-0 group">
                                        <div class="flex justify-between items-start">
                                            <p class="text-sm font-medium text-gray-800 group-hover:text-blue-700 transition-colors"><?php echo htmlspecialchars($notif['text']); ?></p>
                                            
                                            <!-- Indicateur visuel du type (optionnel) -->
                                            <?php if(isset($notif['category']) && $notif['category'] === 'dps'): ?>
                                                <span class="ml-2 text-[10px] uppercase font-bold text-orange-600 bg-orange-100 px-1.5 py-0.5 rounded">DPS</span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1"><?php echo htmlspecialchars($notif['time']); ?></p>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <a href="#" class="block text-center text-xs font-medium text-blue-600 hover:text-blue-800 bg-gray-50 py-2 hover:bg-gray-100 transition">
                                Voir toutes les notifications
                            </a>
                        <?php else: ?>
                            <!-- Cas : Aucune notification -->
                            <div class="px-4 py-8 text-center flex flex-col items-center justify-center">
                                <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.174 5.923 6 8.384 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1"></path></svg>
                                <p class="text-sm text-gray-500 italic">Aucune notif pour le moment</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Nom de l'utilisateur cliquable vers le profil (avec $root) -->
            <a 
                href="<?php echo $root; ?>/profil.php" 
                title="Accéder à votre profil"
                class="hidden sm:inline text-sm text-gray-300 hover:text-white transition cursor-pointer"
            >
                <?php echo htmlspecialchars($userName); ?> [<?php echo htmlspecialchars($userId); ?>]
            </a>
            
            <!-- Icône de Menu (Mobile) -->
            <button class="md:hidden text-gray-300 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>

    </div>
    
    <!-- Script pour gérer l'ouverture/fermeture du dropdown -->
    <script>
        function toggleNotifications() {
            const dropdown = document.getElementById('notif-dropdown');
            dropdown.classList.toggle('hidden');
        }

        // Fermer le dropdown si on clique en dehors
        window.addEventListener('click', function(e) {
            const container = document.getElementById('notif-container');
            const dropdown = document.getElementById('notif-dropdown');
            
            // Si le clic n'est pas dans le conteneur des notifications, on cache le menu
            if (!container.contains(e.target)) {
                if (!dropdown.classList.contains('hidden')) {
                    dropdown.classList.add('hidden');
                }
            }
        });
    </script>

</header>

<main class="container mx-auto p-4 md:p-8">
<!-- Début du contenu spécifique à la page -->