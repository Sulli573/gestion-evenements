<?php 
include 'config/database.php';
include 'controller/UserController.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nom=$_POST['nom'];
    $email=$_POST['email'];
    $mot_de_passe=$_POST['mot_de_passe'];

    $userController=new UserController($db);

    $message=$userController->signup($nom,$email,$mot_de_passe); //va appeler "la methode" de signup

    echo $message;
}

?>