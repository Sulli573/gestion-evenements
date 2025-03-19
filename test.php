<?php

// require_once 'controller/LoginController.php';
//require_once 'config/database.php';
// require_once 'controller/OrganisateurController.php';
// require_once 'models/OrganisateurModel.php';
// require_once 'controller/EvenementController.php';
// require_once 'models/EvenementsModel.php';
// require_once 'controller/InscrireController.php';
// require_once 'models/InscrireModel.php';
require_once 'controller/UserController.php';
require_once 'models/UserModel.php';
require_once 'config/DatabaseManager.php';
require_once 'config/loadEnv.php';
loadEnv("./.env");

$database = new DatabaseManager(
    $_ENV['DB_HOST'],
    (int) $_ENV['DB_PORT'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);

// $eventModel=new UserModel();
// $controller=new UserController($eventModel);
// $controller->signup("EL MATNI","elmatniadam11@gmail.com","Ad@m1234");
echo $_SESSION['csrf_token'];
// $userModel=new UserModel();
// $userController=new UserController($userModel);
// $userController->getUserById(1);

// $data=[
//     "id_user" => 1,
//     "id_event" => 1,
// ];

// $inscrireModel=new InscrireModel();
// $inscrireController=new InscrireController($inscrireModel);
// $inscrireController->retirerInscription($data);

#Pour tester UserModel
// $data=[
//     "nom_organisateur" => "Sullivan ESPEUT",
//     "contact_organisateur" => "0655190899",
//     "email_organisateur" => "sullivan.espeut@gmail.com"
// ];



// $data=[
//     "id_evenement" => 1,
//     "nom_evenement" => "concert musique",
//     "date_evenement" => "2025-09-24",
//     "description_evenement" => "super concert de musique classique",
//     "place_evenement" => 5,
//     "image_evenement" => "concert.jpg",
//     "type_evenement" => "concerto",
//     "id_lieu" => 1
// ];


#pour tester EvenementsModel
// $model=new OrganisateurModel();
// $controller=new OrganisateurController($model);
// $controller->deleteOrganisateurById(1);



// var_dump($database->getPDO());




// $email='1@gmail.com';
// $mdp='1';

// $loginController->login($email,$mdp);

// $user=$userModel->getUserByEmail($email);


// if(password_verify($mdp,$user['mot_de_passe_utilisateur'])){
//     echo'<br> le mot de passe est correcte';
// }else{
//     echo'le mot de passe est incorrecte';
// }

// if(isset($_SESSION['id_utilisateur'])){ //isset vérifie si l'utilisateur existe dans la session
//     echo "Connexion réussie : <br>";
//     echo"Id Utilisateur: " .$_SESSION['id_utilisateur'];
//     echo"Nom Utilisateur: " .$_SESSION['nom_utilisateur'];
//     echo"Role Utilisateur: " .$_SESSION['role_utilisateur'];
// }else{
//     echo 'connection perdue';
// }

// $EvenementModel = new EvenementsModel();
// var_dump($EvenementModel->afficheInfoEvenement(1));



?>