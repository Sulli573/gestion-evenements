<?php
require_once __DIR__ . '/../config/DatabaseManager.php';
require_once __DIR__ . '/../config/loadEnv.php';
require_once 'auth.php';
loadEnv(__DIR__ . '/../.env');

$database = new DatabaseManager(
    $_ENV['DB_HOST'],
    (int) $_ENV['DB_PORT'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);

if($_SESSION['role_utilisateur'] !== 'admin') {
    echo "Accès refusé";
    exit;
}
?> 