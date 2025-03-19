<?php
require_once __DIR__ . '/../config/sessionManager.php';

class LieuController{
//private $userModel; /* car on va utiliser la methode get email */
private $LieuModel;

    public function __construct($LieuModel){
        $this->LieuModel=$LieuModel;
    }       
 # je vérifie les données 
    public function create($data, $csrf_token){
        if(!isset($csrf_token) || $csrf_token!==$_SESSION['csrf_token']){
            return json_encode((["status" => "Erreur","message" => "Token CSRF invalide"]));
        }
        
        $required_fields=['nom_lieu','adresse_lieu'];
        foreach ($required_fields as $field){
        if(empty($data[$field])){
                echo json_encode(["status" => "error","message" => "Le champ " .$field." est requis"]);
            return;
            }
        }
        $nom=htmlspecialchars(trim($data['nom_lieu']),ENT_QUOTES,'UTF-8');
        $adresse=htmlspecialchars(trim($data['adresse_lieu']),ENT_QUOTES,'UTF-8');

        try{
            $result=$this->LieuModel->create($nom,$adresse);
            return json_encode(["status" => $result ? "success":"erreur","message" => $result ? "Lieu ajoute avec succes" : "Erreur lors de l'ajout"]);
        }
        catch(Exception $e){
            echo json_encode(["status" => "error","message" => "Erreur serveur : ".$e->getMessage()]);
        }
    }

    public function getAllLieux(){
        try{
            $lieux=$this->LieuModel->getAll();
            if(!empty($lieux)){
                return json_encode([
                    "status" => "Success",
                    "lieux" => $lieux
                ]);
            }else{
                return json_encode([
                    "status" => "Success",
                    "message" => "Aucun evenement trouve"
                ]);
            }
        }catch(Exception $e){
        echo json_encode(["status" => "error","message" => "Erreur serveur : ".$e->getMessage()]);
        }
    }

    public function updateLieu($data, $csrf_token) {

        if(!isset($csrf_token) || $csrf_token!==$_SESSION['csrf_token']){
                echo json_encode((["status" => "Erreur","message" => "Token CSRF invalide2"]));
                return;
            }

        $required_fields = ['nom_lieu', 'adresse_lieu'];

        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                echo json_encode(["status" => "error", "message" => "Le champ " . $field . " est requis"]);
                return;
            }
        }
        $id_lieu = filter_var(trim($data['id_lieu']), FILTER_VALIDATE_INT);
        if (!$id_lieu) {
            return json_encode(["status" => "error", "message" => "ID lieu invalide"]);
        }

        $nom_lieu = htmlspecialchars(trim($data['nom_lieu']), ENT_QUOTES, 'UTF-8');
        $adresse_lieu = htmlspecialchars(trim($data['adresse_lieu']), ENT_QUOTES, 'UTF-8');

        try {
            $result = $this->LieuModel->update($id_lieu, $nom_lieu, $adresse_lieu);
            return json_encode(["status" => $result ? "success" : "erreur", "message" => $result ? "Lieu modifiee avec succes" : "Erreur lors de la modification"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Erreur serveur : " . $e->getMessage()]);
        }
    }
    public function getById($id_lieu) {
        if(empty($id_lieu) || !is_numeric($id_lieu)) {
            echo json_encode(["status" => "error", "message" =>"Id invalide"]);
            return;
        }try {
            $lieu=$this->LieuModel->getbyId($id_lieu);
        if(!empty($lieu)) {
            return json_encode([
                "status" => "success",
                "lieu" => $lieu
            ]);
        }else{
            return json_encode([
                "status" => "success",
                "message" => "Aucun lieu trouvé"
            ]);
        }}catch(Exception $e) {
            echo json_encode(["status" => "error","message" => "Erreur serveur : ".$e->getMessage()]);
        }
    }
    

    public function deleteLieuxById($id_lieu)
    {
        $id_lieu = filter_var(trim($id_lieu), FILTER_VALIDATE_INT);
        if (!$id_lieu) {
            return json_encode(["status" => "error", "message" => "ID lieu invalide"]);
        }

        try {
            $lieu = $this->LieuModel->delete($id_lieu);
            if ($lieu) {
                return json_encode([
                    "status" => "success",
                    "message" => "Lieu supprimé avec succès"
                ]);
            } else {
                return json_encode([
                    "status" => "success",
                    "message" => $lieu
                ]);
            }
        } catch (Exception $e) {
            return json_encode(["status" => "error", "message" => "Erreur serveur : " . $e->getMessage()]);
        }
    }
}
    ?>