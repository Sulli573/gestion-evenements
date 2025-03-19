<?php

require_once __DIR__ . '/vendor/autoload.php'; //Charger AltoRouter
require_once __DIR__ . '/config/DatabaseManager.php';
require_once __DIR__ . '/config/loadEnv.php';

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
    echo json_encode(["status" => "error","message" => "Page non trouvee"]);
}


//le contenu de la variable $match
// $match=[
//     'target'=> function($id) use ($eventController){
//         echo $eventController->getEventById($id);
//     },

//     'parms' => [
//         'id' =>3
//     ],
//     'name'=>null
// ]
?>
