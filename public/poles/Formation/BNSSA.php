<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . '/../../../src/database.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Pôle BNSSA - CRM IntraSomme</title>

    <!-- Styles -->
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/poles.css">


    <!-- FullCalendar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
</head>
<body>

<header><h1>Pôle Formation – BNSSA</h1></header>

<div class="pole-wrapper">

    <!-- Calendrier BNSSA -->
    <section class="block">
        <h2>Calendrier BNSSA</h2>
        <div id="calendar-bnssa"></div>
    </section>


    <!-- Formateurs -->
    <section class="block">
        <h2>Inscription Formateurs BNSSA</h2>
        <form method="POST" action="/INTRASOMME/public/poles/formation/formateurs/bnssa_inscription.php">
            <label>Choisir un créneau :</label>
            <select name="event_id" id="formateur-event" required>
                <?php
                $stmt = $pdo->prepare("SELECT * FROM events WHERE pole='bnssa' ORDER BY start ASC");
                $stmt->execute();
                $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($events as $ev) {
                    echo "<option value='{$ev['id']}'>".date("d/m H:i", strtotime($ev['start']))." - ".htmlspecialchars($ev['title'])."</option>";
                }
                ?>
            </select>
            <button type="submit">S'inscrire</button>
        </form>
    </section>

</div>

<?php include __DIR__ . '/../../footer.php'; ?>

<script src="\IntraSomme\public\js\bnssa_interactive.js"></script>


<!-- Formulaire ajout / modification d'événements -->
<section class="block">
    <h2>Ajouter / Modifier / Supprimer une formation</h2>

    <form id="event-form">
    <input type="hidden" name="id" id="event-id">
    
    <label for="title">Titre :</label>
    <input type="text" name="title" id="title" placeholder="Nom de la formation" required>

    <label for="start">Date début :</label>
    <input type="datetime-local" name="start" id="start" required>

    <label for="end">Date fin :</label>
    <input type="datetime-local" name="end" id="end" required>

    <label for="description">Description :</label>
    <textarea name="description" id="description" placeholder="Description de la formation"></textarea>

    <button type="submit">Ajouter / Modifier</button>
</form>

</section>


</body>
</html>
