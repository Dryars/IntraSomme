<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Chemin serveur
$folder = __DIR__ . '/../public/essentiel/';

// Récupère les fichiers PDF
$files = glob($folder . '*.pdf');

if (!empty($files)) {
    usort($files, function($a, $b) {
        return filemtime($b) - filemtime($a);
    });
    $latestFile = $files[0];
    $latestFileName = basename($latestFile);

    // URL publique pour navigateur
    $pdfUrl = '/IntraSomme/public/essentiel/' . urlencode($latestFileName);
} else {
    $latestFile = null;
    $pdfUrl = '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Essentiel - CRM IntraSomme</title>
<link rel="stylesheet" href="/IntraSomme/public/css/style.css">
</head>
<body>

<?php include __DIR__ . '/header.php'; ?>

<div class="pdf-container">
<?php if ($latestFile): ?>
    <iframe src="<?= $pdfUrl ?>" width="100%" height="2600px"></iframe>
    <br>
    <a href="<?= $pdfUrl ?>" download>Télécharger le PDF</a>
<?php else: ?>
    <p>Aucun fichier PDF trouvé.</p>
<?php endif; ?>
</div>

<?php include __DIR__ . '/footer.php'; ?>
<script src="/IntraSomme/public/js/essentiel.js"></script>
</body>
</html>
