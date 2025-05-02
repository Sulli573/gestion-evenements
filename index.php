<?php

require_once __DIR__ . '/vendor/autoload.php'; //Charger AltoRouter
require_once __DIR__ . '/config/DatabaseManager.php';
require_once __DIR__ . '/config/loadEnv.php';
require_once __DIR__ . '/config/sessionManager.php';


loadEnv(__DIR__ . '/.env');

$database = new DatabaseManager(
    $_ENV['DB_HOST'],
    (int) $_ENV['DB_PORT'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);

$router=new AltoRouter();
$router->setBasePath('/PHP2');
require_once __DIR__ . '/routes/apiRoutes.php';


$match=$router->match();


if($match && is_callable($match['target'])) {
    call_user_func_array($match['target'],$match['params']);
}else{
    http_response_code(404);
    require_once __DIR__ . '/views/partial/error-page-404.html';
    exit;
}

?>
