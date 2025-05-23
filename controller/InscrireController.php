<?php
require_once __DIR__ . '/../config/sessionManager.php';

class InscrireController{
    //private $userModel; /* car on va utiliser la methode get email */
    private $inscrireModel;
    public function __construct($inscrireModel){ 
        $this->inscrireModel=$inscrireModel;
    }

    public function create($data,$csrf_token){
        if(!isset($csrf_token) || $csrf_token!==$_SESSION['csrf_token']){
            return json_encode((["status" => "Erreur","message" => "Token CSRF invalide"]));
        }

        $required_fields=['id_user','id_event','nbr_ticket'];
        foreach($required_fields as $field){
            if(empty($data[$field])){
                echo json_encode(["status" => "error","message" => "Le champ ".$field." est requis"]);
                return;
            }
        }

        $id_user=filter_var($data['id_user'],FILTER_VALIDATE_INT);
        $id_event=filter_var($data['id_event'],FILTER_VALIDATE_INT);
        $nbr_ticket=filter_var($data['nbr_ticket'],FILTER_VALIDATE_INT);
        if(!$id_event || !$id_user){
            return json_encode(["status" => "error","message" => "ID event ou ID user invalide!!"]);
        }

        try{
            $result=$this->inscrireModel->create($id_user,$id_event,$nbr_ticket);
            return json_encode(["status" => $result ? "success":"erreur","message" => $result ? "inscription ajoute avec succes" : "Erreur lors de l'inscription"]);
        }catch(Exception $e){
            echo json_encode(["status" => "error","message" => "Erreur serveur : ".$e->getMessage()]);
        }
    }

    public function retirerInscription($data,$csrf_token){
        if(!isset($csrf_token) || $csrf_token!==$_SESSION['csrf_token']){
            return json_encode((["status" => "Erreur","message" => "Token CSRF invalide"]));
        }

        $required_fields=['id_user','id_event'];
        foreach($required_fields as $field){
            if(empty($data[$field])){
                echo json_encode(["status" => "error","message" => "Le champ ".$field." est requis"]);
                return;
            }
        }

        $id_user=filter_var($data['id_user'],FILTER_VALIDATE_INT);
        $id_event=filter_var($data['id_event'],FILTER_VALIDATE_INT);
        if(!$id_event || !$id_user){
            return json_encode(["status" => "error","message" => "ID event ou ID user invalide!!"]);
        }
        try{
            $result=$this->inscrireModel->retirerInscription($id_user,$id_event);
            return json_encode(["status" => $result ? "success":"erreur","message" => $result ? "inscription retirer avec succes" : "Erreur lors de la desinscription"]);
        }catch(Exception $e){
            echo json_encode(["status" => "error","message" => "Erreur serveur : ".$e->getMessage()]);
        }
    }
    public function afficherInscriptionByUserId($userId) {
        if(empty($userId) || !is_numeric($userId)) {
            echo json_encode(["status" => "error", "message" =>"Id invalide"]);
            return;
    }
    try {
        $inscription=$this->inscrireModel->afficherInscriptionByUserId($userId);
        if(!empty($inscription)) {
            return json_encode([
                "status" => "success",
                "inscription" => $inscription
            ]);
        }else{
            return json_encode([
                "status" => "success",
                "message" => "Aucune inscription trouvée"
            ]);
        }
    }catch(Exception $e) {
        echo json_encode(["status" => "error","message" => "Erreur serveur : ".$e->getMessage()]);
    }
    }
}