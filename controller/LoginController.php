<?php
require_once __DIR__ . '/../config/sessionManager.php';

class LoginController {
private $userModel; /* car on va utiliser la methode get email */

public function __construct($userModel){
    $this->userModel=$userModel;
}

public function login($email,$mot_de_passe){
    $email=filter_var(trim($email),FILTER_VALIDATE_EMAIL);
    $mot_de_passe=trim($mot_de_passe);

    if(!$email || empty($mot_de_passe)){
        return json_encode((["status" => "Erreur","message" => "Email ou mot de passe incorrecte"]));
    }

    $user=$this->userModel->getUserByEmail($email); 
                                                    /* $email est l'email qui va être entré dans le champs, et on doit recuperer l'utilisateur qui à cet email (recupérer le reste (nom etc...)) exemeple on recupérer l'utilisateur qui a email:sulli@gmail.com mdp=12345 */
    if($user && password_verify($mot_de_passe,$user['mot_de_passe_utilisateur'])){ // vérification si l'email et mdp de passe correspondent entre ce qui a été entré dans les champs et la base de données 
        //connexion reussie
        
        $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
        $_SESSION['nom_utilisateur'] = htmlspecialchars($user['nom_utilisateur'],ENT_QUOTES,'UTF-8');
        $_SESSION['role_utilisateur'] = htmlspecialchars($user['role_utilisateur'],ENT_QUOTES,'UTF-8'); //recuperation des données

        if ($user['role_utilisateur'] === 'admin'){
            return json_encode((["status" => "Success","message" => "Connexion reussie - role adminstrateur"]));
        }else{
            return json_encode((["status" => "Success","message" => "Connexion reussie - role utilisateur"]));
        }    
        //exit(); utilisé quand tout sera fait (la page front)
    }else{
        return json_encode((["status" => "erreur","message" => "Email ou mot de passe incorrecte"]));
    }
 }                                                  
}

?>