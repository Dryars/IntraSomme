<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Pages accessibles sans authentification
 */
function publicPages(): array {
    return [
        'login.php',
        'login_process.php',
        'logout.php'
    ];
}

/**
 * Protection globale du site
 */
function protectSite(): void {
    $currentPage = basename($_SERVER['PHP_SELF']);

    if (empty($_SESSION['user_id']) && !in_array($currentPage, publicPages())) {
        header('Location: /login.php');
        exit;
    }
}

/**
 * Oblige l'utilisateur à être connecté
 */
function requireLogin(): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: /login.php');
        exit;
    }
}

/**
 * Vérifie les rôles autorisés
 */
function requireRole($roles): void {
    if (
        empty($_SESSION['role']) ||
        !in_array($_SESSION['role'], (array)$roles, true)
    ) {
        header('Location: /unauthorized.php');
        exit;
    }
}

/**
 * Vérifie si l'email est validé
 */
function isEmailValidated(): bool {
    return !empty($_SESSION['email_validated']);
}
