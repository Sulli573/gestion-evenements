<?php
require_once __DIR__ . '/../config/sessionManager.php';

class UserController {
        private $userModel;

        public function __construct($userModel)
        {
            $this->userModel=$userModel;
        }

        public function signup($nom,$email,$mot_de_passe){
            $nom=htmlspecialchars(trim($nom),ENT_QUOTES,'UTF-8');
            $email=filter_var(trim($email),FILTER_VALIDATE_EMAIL);
            $mot_de_passe=trim($mot_de_passe);

            if(empty($nom) || empty($email) || empty($mot_de_passe)){ //vérifie que tous les champs sont remplis
                //si tous les champs ne sont pas remplis affiche ce message
                return json_encode((["status" => "Erreur","message" => "Tous les champs sont obligatoires"]));
            }

            if(!$email){//equivalent a false, null, 0, "0", "", [] ou false
                return json_encode((["status" => "Erreur","message" => "Email invalide"]));
            }
            
            $mot_de_passe_hash=password_hash($mot_de_passe,PASSWORD_DEFAULT);  //stockage du resultat du mot de passe haché 

            $result=$this->userModel->createUser($nom,$email,$mot_de_passe_hash);
            
            return json_encode(["status" => $result ? "success":"erreur","message" => $result ? "Inscription reussie" : "Inscription echouee"]);
        }

        public function getAllUsers(){
            try{
                $users=$this->userModel->getAll();
                if(!empty($users)){
                    return json_encode([
                        "status" => "Success",
                        "users" => $users
                    ]);
                }else{
                    return json_encode([
                        "status" => "Success",
                        "message" => "Aucun utilisateur trouve"
                    ]);
                }
            }catch(Exception $e){
            return json_encode(["status" => "error","message" => "Erreur serveur : ".$e->getMessage()]);
            }
        }

        public function getUserById($id_user){
            if(empty($id_user) || !is_numeric($id_user)){
                echo json_encode(["status" => "error","message" => "Id invalide"]);
                return;
            }
            try{
                $user=$this->userModel->getById($id_user);
                if(!empty($user)){
                    echo json_encode([
                        "status" => "Success",
                        "users" => $user
                    ]);
                }else{
                    echo json_encode([
                        "status" => "Success",
                        "message" => "Aucun utilisateur trouve"
                    ]);
                }
            }catch(Exception $e){
                echo json_encode(["status" => "error","message" => "Erreur serveur : ".$e->getMessage()]);
            }
        }

        public function updateUser($id_user, $nom, $email, $role, $is_suspended, $is_active, $motif_suspension,$csrf_token) {
            
            if(!isset($csrf_token) || $csrf_token!==$_SESSION['csrf_token']){
                echo json_encode((["status" => "Erreur","message" => "Token CSRF invalide"]));
                return;
            }
            if (empty($id_user) || !is_numeric($id_user)) {
                return json_encode(["status" => "error", "message" => "ID utilisateur invalide"]);
            }

            try {
                $result = $this->userModel->update($id_user, $nom, $email, $role, $is_suspended, $is_active, $motif_suspension);
                return json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Mise à jour réussie" : "Mise à jour échouée"]);
            } catch(Exception $e) {
                return json_encode(["status" => "error", "message" => "Erreur serveur : " . $e->getMessage()]);
            }
        }

        public function deleteUserById($id_user,$csrf_token) {
            if(!isset($csrf_token) || $csrf_token!==$_SESSION['csrf_token']){
                echo json_encode((["status" => "Erreur","message" => "Token CSRF invalide"]));
                return;
            }

            if (!is_numeric($id_user)){
                echo json_encode([
                    "status" => "error",
                    "message" => "ID utilisateur invalide."
                ]);
                return;
            }
            try {
                $result = $this->userModel->delete($id_user);  
                echo json_encode(["status" => $result ? "success":"erreur","message" => $result ? "Supression reussie" : "Supression echouee"]);
                return;
            } catch (Exception $e) {
                echo json_encode([
                    "status" => "error",
                    "message" => "Erreur serveur : " . $e->getMessage()
                ]);
            }
        }
    }
?>
