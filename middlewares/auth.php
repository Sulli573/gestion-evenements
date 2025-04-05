<?php
require_once __DIR__ . '/../config/sessionManager.php';
require_once __DIR__ . '/../models/TokenModel.php';
require_once __DIR__ . '/../config/DatabaseManager.php';
require_once __DIR__ . '/../config/loadEnv.php';
loadEnv(__DIR__ . '/../.env');

$database = new DatabaseManager(
    $_ENV['DB_HOST'],
    (int) $_ENV['DB_PORT'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);

$tokenModel = new TokenModel();
//vérification de la session et du token
if(!isset($_SESSION['id_utilisateur'], $_SESSION['token'])) {
    header("Location: /PHP2/views/template/login-template.php"); 
    exit;
}
if(!$tokenModel->validateToken($_SESSION['id_utilisateur'],$_SESSION['token'])) {
    $_SESSION=[];
    session_destroy();
    header("Location: /PHP2/views/template/login-template.php"); 
    exit;
}
?>