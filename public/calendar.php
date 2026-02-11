<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Calendrier CFI - CRM IntraSomme</title>
<link rel="stylesheet" href="css/style.css">

<!-- FullCalendar via CDN -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/main.min.css" rel="stylesheet">

<style>
    #calendar {
        max-width: 1200px;
        margin: 20px auto;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 15px;
        height: 600px;
    }
    .fc-toolbar-title {
        color: #1E40AF;
        font-weight: 600;
        font-size: 1.3rem;
    }
    .fc-button {
        background-color: #F97316;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 5px 12px;
    }
    .fc-button:hover {
        background-color: #EA580C;
    }
</style>
</head>
<body>
<header><h1>Calendrier CFI</h1></header>

<div id="calendar"></div>

<?php include __DIR__ . '/footer.php'; ?>

<!-- FullCalendar JS via CDN -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/main.min.js"></script>
<script src="js/calendar.js"></script>
</body>
</html>
