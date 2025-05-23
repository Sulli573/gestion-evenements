<?php
require_once __DIR__ . '/../models/EvenementsModel.php';
require_once __DIR__ . '/../controller/EvenementController.php';
require_once __DIR__ . '/../controller/UserController.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/TokenModel.php';
require_once __DIR__ . '/../controller/LieuController.php';
require_once __DIR__ . '/../controller/OrganisateurController.php';
require_once __DIR__ . '/../controller/InscrireController.php';
require_once __DIR__ . '/../controller/LoginController.php';
require_once __DIR__ . '/../models/InscrireModel.php';
require_once __DIR__ . '/../models/LieuModel.php';
require_once __DIR__ . '/../models/OrganisateurModel.php';
require_once __DIR__ . '/../config/sessionManager.php';



$eventModel=new EvenementsModel();
$eventController=new EvenementController($eventModel);

$userModel=new UserModel();
$userController=new UserController($userModel);

$lieuModel = new LieuModel($database);
$lieuController = new LieuController($lieuModel);

$organisateurModel = new OrganisateurModel($database);
$organisateurController = new OrganisateurController($organisateurModel);

$inscrireModel = new InscrireModel($database);
$inscrireController = new InscrireController($inscrireModel);

$loginController = new LoginController($userModel);

$tokenModel = new TokenModel();

//Les routes pour les utilisateurs
$router->map('GET','/api/users',function() use ($userController){
    echo $userController->getAllUsers();
});

$router->map('POST','/api/users/update',function() use ($userController){
    $data=$_POST;
    $csrf_token=$_POST['csrf_token'] ?? '';
    
    echo $userController->updateUser($data['id_utilisateur'],$data['nom_utilisateur'],$data['courriel_utilisateur'],
    $data['role_utilisateur'],$data['is_active'],$data['is_suspended'],$data['motif_suspension'],$csrf_token);
});

//delete
$router->map('POST','/api/users/delete',function() use ($userController){
    $data = json_decode(file_get_contents("php://input"), true);

    $id_user=$data['id_utilisateur'] ?? null;
    $csrf_token=$data['csrf_token'] ?? '';
    
    echo $userController->deleteUserById($id_user,$csrf_token);
});
//logout
$router->map('POST','/api/logout',function() use ($loginController) {
    echo $loginController->logout();
});

$router->map('POST','/api/users/is-logged-in',function() use ($tokenModel){
    echo $tokenModel->validateToken($_SESSION['id_utilisateur'],$_SESSION['token']);
    
});


//Les routes pour les evenements
$router->map('GET','/api/events',function() use ($eventController){
    echo $eventController->getAllEvenements();
});

$router->map('GET','/api/events/[i:id]',function($id) use ($eventController){
    echo $eventController->getEventById($id);
});

$router->map('POST','/api/event/update',function() use ($eventController){
    $data=$_POST;
    $csrf_token=$_POST['csrf_token'] ?? '';
    
    echo $eventController->update($data,$csrf_token);
});

$router->map('POST','/api/event/create',function() use ($eventController){
    $data=$_POST;
    $csrf_token=$_POST['csrf_token'] ?? '';
    
    echo $eventController->create($data,$csrf_token);
});

//delete
$router->map('POST','/api/event/delete',function() use ($eventController){
    $data = json_decode(file_get_contents("php://input"), true);

    $id_event=$data['id_evenement'] ?? null;
    $csrf_token=$data['csrf_token'] ?? '';
    
    echo $eventController->deleteEventById($id_event,$csrf_token);
});

//Les routes pour lieux
//add
$router->map('POST','/api/lieux/add',function() use ($lieuController){
    $data = json_decode(file_get_contents("php://input"), true);

    $data=$_POST;
    $csrf_token=$_POST['csrf_token'] ?? '';
    
    echo $lieuController->create($data,$csrf_token);
});
//getall
$router->map('GET','/api/lieux',function() use ($lieuController){
    echo $lieuController->getAllLieux();
});
//getById
$router->map('GET','/api/lieux/[i:id]',function($id) use ($lieuController){
    echo $lieuController->getById($id);
});
//update
$router->map('POST','/api/lieux/update',function() use ($lieuController){
    $data=$_POST;
    $csrf_token=$_POST['csrf_token'] ?? '';
    
    echo $lieuController->updateLieu($data,$csrf_token);
});
//delete
$router->map('POST','/api/lieux/delete',function() use ($lieuController){
    $data = json_decode(file_get_contents("php://input"), true);

    $id_lieu=$data['id'] ?? null;
    $csrf_token=$data['csrf_token'] ?? '';
    
    echo $lieuController->deleteLieuxById($id_lieu,$csrf_token);
});




//Les routes pour les organisateurs
//add
$router->map('POST','/api/organisateurs/add',function() use ($organisateurController){
    $data = json_decode(file_get_contents("php://input"), true);

    $data=$_POST;
    $csrf_token=$_POST['csrf_token'] ?? '';
    
    echo $organisateurController->create($data,$csrf_token);
});
//getAll
$router->map('GET','/api/organisateurs',function() use ($organisateurController){
    echo $organisateurController->getAllOrganisateurs();
});
//update
$router->map('POST','/api/organisateurs/update',function() use ($organisateurController){
    $data=$_POST;
    $csrf_token=$_POST['csrf_token'] ?? '';
    
    echo $organisateurController->updateOrganisateur($data,$csrf_token);
});
//delete
$router->map('POST','/api/organisateurs/delete',function() use ($organisateurController){
    $data = json_decode(file_get_contents("php://input"), true);

    $id_orga=$data['id'] ?? null;
    $csrf_token=$data['csrf_token'] ?? '';
    
    echo $organisateurController->deleteOrganisateurById($id_orga,$csrf_token);
});

// Les routes d'achat des tickets

//Achat/S'inscrire
$router->map('POST','/api/inscrire/create',function() use ($inscrireController){
    $data=$_POST;
    $csrf_token=$_POST['csrf_token'] ?? '';
    
    echo $inscrireController->create($data,$csrf_token);
});

$router->map('GET','/api/inscrire',function() use ($inscrireController){
    $userId=$_SESSION['id_utilisateur'];
    echo $inscrireController->afficherInscriptionByUserId($userId);
});




?>