<?php
// Vérifier si la session est déjà active avant de définir les paramètres
if (session_status() === PHP_SESSION_NONE) {
    // Définir les paramètres avant de démarrer la session
    ini_set('session.gc_maxlifetime', 3600); // 1 heure
    session_set_cookie_params([
        'lifetime' => 3600, // Durée de vie du cookie de session
        'path' => '/',
        // 'domain' => '', // Modifier si nécessaire
        'secure' => isset($_SERVER['HTTPS']), // Activer en HTTPS
        'httponly' => true, // Empêcher l'accès via JavaScript
        'samesite' => 'Strict' // Protection contre les attaques CSRF via d'autres sites
    ]);

    session_start();

// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
// exit;

}

// Initialiser le token CSRF si nécessaire
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
