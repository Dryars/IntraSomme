<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Calendrier BNSSA</title>
<link rel="stylesheet" href="/INTRASOMME/public/css/style.css">

<!-- FullCalendar -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

</head>
<body>

<header><h1>Planning BNSSA</h1></header>

<div id="calendar"></div>

<script src="/INTRASOMME/public/js/calendar_bnssa.js"></script>

<?php include __DIR__ . '/../../../footer.php'; ?>

</body>
</html>
