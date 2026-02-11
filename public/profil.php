<?php
// Fichier : profil.php
// On inclut le header qui contient la session, les variables utilisateur et le début du HTML
include __DIR__ . '/header.php'; 

// Simulation de données supplémentaires pour le profil (en production, viendrait de la BDD)
$userEmail = "hadrien.cfi@intrasomme.fr";
$userRole = "Super Admin";
$userService = "Pôle Formation";
$userPhone = "06 12 34 56 78";
$lastLogin = date("d/m/Y à H:i");
?>

<!-- Contenu de la page Profil -->
<div class="max-w-5xl mx-auto">
    
    <!-- En-tête du Profil -->
    <div class="mb-8 flex flex-col md:flex-row items-center md:items-start gap-6">
        <!-- Avatar / Initiale -->
        <div class="w-24 h-24 bg-blue-800 text-white rounded-full flex items-center justify-center text-3xl font-bold shadow-lg border-4 border-white">
            <?php echo strtoupper(substr($userName, 0, 1)); ?>
        </div>
        
        <div class="text-center md:text-left flex-1">
            <h1 class="text-3xl font-bold text-gray-800"><?php echo htmlspecialchars($userName); ?></h1>
            <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-2">
                <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full border border-green-200">Actif</span>
                <span class="px-3 py-1 text-xs font-semibold text-purple-700 bg-purple-100 rounded-full border border-purple-200"><?php echo htmlspecialchars($userRole); ?></span>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex gap-3 mt-4 md:mt-0">
            <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg shadow-sm hover:bg-gray-50 font-medium text-sm transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Modifier
            </button>
        <form action="logout.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <button type="submit"
            class="px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 font-medium text-sm transition flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Déconnexion
            </button>
        </form>

        </div>
    </div>

    <!-- Grille d'informations -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Carte Informations Personnelles -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Informations Personnelles</h2>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-sm font-medium text-gray-500">Email</div>
                    <div class="col-span-2 text-sm text-gray-900 font-medium"><?php echo htmlspecialchars($userEmail); ?></div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-sm font-medium text-gray-500">Téléphone</div>
                    <div class="col-span-2 text-sm text-gray-900"><?php echo htmlspecialchars($userPhone); ?></div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-sm font-medium text-gray-500">Adresse</div>
                    <div class="col-span-2 text-sm text-gray-900">Centre de Formation CFI<br>80000 Amiens, France</div>
                </div>
            </div>
        </div>

        <!-- Carte Informations Professionnelles -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Poste & Activité</h2>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15.75c-2.438 0-4.823-.483-7.08-1.495M13.818 10.383a11.96 11.96 0 0111.488-3.085A23.931 23.931 0 0012 5.75c-2.438 0-4.823.483-7.08 1.495M5.196 13.255A23.931 23.931 0 0112 15.75c2.438 0 4.823-.483 7.08-1.495M10.182 8.383a11.96 11.96 0 00-11.488 3.085A23.931 23.931 0 0112 18.25c2.438 0 4.823-.483 7.08-1.495M12 21.25c-5.187 0-9.408-4.221-9.408-9.408S6.813 2.434 12 2.434s9.408 4.221 9.408 9.408S17.187 21.25 12 21.25z"></path></svg>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-sm font-medium text-gray-500">Service</div>
                    <div class="col-span-2 text-sm text-gray-900"><?php echo htmlspecialchars($userService); ?></div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-sm font-medium text-gray-500">Dernière connexion</div>
                    <div class="col-span-2 text-sm text-gray-900"><?php echo htmlspecialchars($lastLogin); ?></div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-sm font-medium text-gray-500">Responsable N+1</div>
                    <div class="col-span-2 text-sm text-gray-900">LEFEVRE Pierre</div>
                </div>
            </div>
        </div>

        <!-- Section Sécurité / Préférences (Pleine largeur) -->
        <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
             <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">Paramètres de Sécurité</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Mot de passe</p>
                        <p class="text-xs text-gray-500">Dernière modification il y a 3 mois</p>
                    </div>
                    <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">Changer</button>
                </div>
                <div class="flex items-center justify-between py-3">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Authentification à deux facteurs (2FA)</p>
                        <p class="text-xs text-gray-500">Ajoutez une couche de sécurité supplémentaire à votre compte</p>
                    </div>
                    <button class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none bg-gray-200" role="switch" aria-checked="false">
                        <span class="translate-x-0 pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"></span>
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<?php 
// Inclusion du pied de page simple pour fermer les balises ouvertes par header.php
include __DIR__ . '/footer.php'; 
?>