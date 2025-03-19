<?php
require_once __DIR__ . '/../config/sessionManager.php';

class OrganisateurController{
    private $organisateurmodel;

    public function __construct($organisateurmodel){
        $this->organisateurmodel=$organisateurmodel;
    }

    public function create($data,$csrf_token){
        if(!isset($csrf_token) || $csrf_token!==$_SESSION['csrf_token']){
            return json_encode((["status" => "Erreur","message" => "Token CSRF invalide"]));
        }
        $required_fields=['nom_organisateur','contact_organisateur','email_organisateur'];
        foreach($required_fields as $field){
            if(empty($data[$field])){
                echo json_encode(["status" => "error","message" => "Le champ ".$field." est requis"]);
                return;
            }
        }
        $nom=htmlspecialchars(trim($data['nom_organisateur']),ENT_QUOTES,'UTF-8');
        $contact=htmlspecialchars(trim($data['contact_organisateur']),ENT_QUOTES,'UTF-8');
        $email=filter_var(trim($data['email_organisateur']),FILTER_VALIDATE_EMAIL);
        if(!$email){
            return json_encode((["status" => "Erreur","message" => "Email invalide"]));
        }
        try{
            //instancier le modele
            // $this->organisateurmodel=new OrganisateurModel();
            $result=$this->organisateurmodel->createOrganisateur($nom,$contact,$email);
            return json_encode(["status" => $result ? "success":"erreur","message" => $result ? "Organisateur cree avec succes" : "Erreur lors de la creation"]);
        }catch(Exception $e){
            echo json_encode(["status" => "error","message" => "Erreur serveur : ".$e->getMessage()]);
        }
    }

    public function getAllOrganisateurs(){
        try{
            $organisateurs=$this->organisateurmodel->getAll();
            #si la base de données n'est pas vide
            if(!empty($organisateurs)){
                return json_encode([
                    "status" => "Succes",
                    "organisateurs" => $organisateurs
                ]);
            }else{
                return json_encode([
                    "status" => "Succes",
                    "message" => "Aucun organisateur trouvé"
                ]);
            }
        }catch(Exception $e){
            return json_encode(["status" => "error","message" => "Erreur serveur : ".$e->getMessage()]);
        }
    }
    public function getOrganisateurById($id_orga) {
            #si la base de données n'est pas vide  ????? bon commentaire ???
            $id_orga=filter_var(trim($id_orga),FILTER_VALIDATE_INT);
            if(!$id_orga){
                return json_encode((["status" => "Erreur","message" => "ID organisateur invalide"]));
            }
            try {
                $orga=$this->organisateurmodel->getbyId($id_orga);
                return json_encode(["status" => "success","organisateur" => $orga ?: "Aucun utilisateur trouve"]);
            }catch(Exception $e){
                echo json_encode(["status" => "error","message" => "Erreur serveur : ".$e->getMessage()]);
    }
}
public function updateOrganisateur($data, $csrf_token) {

    if(!isset($csrf_token) || $csrf_token!==$_SESSION['csrf_token']){
            echo json_encode((["status" => "Erreur","message" => "Token CSRF invalide2"]));
            return;
        }

    $required_fields = ['nom_organisateur', 'contact_organisateur','email_organisateur'];

    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            echo json_encode(["status" => "error", "message" => "Le champ " . $field . " est requis"]);
            return;
        }
    }
    $id = filter_var(trim($data['id']), FILTER_VALIDATE_INT);
    if (!$id) {
        return json_encode(["status" => "error", "message" => "ID organisateur invalide"]);
    }

    $nom_organisateur = htmlspecialchars(trim($data['nom_organisateur']), ENT_QUOTES, 'UTF-8');
    $contact_organisateur = htmlspecialchars(trim($data['contact_organisateur']), ENT_QUOTES, 'UTF-8');
    $email_utilisateur = htmlspecialchars(trim($data['email_organisateur']), ENT_QUOTES, 'UTF-8');

    try {
        $result = $this->organisateurmodel->update($id, $nom_organisateur, $contact_organisateur,$email_utilisateur);
        return json_encode(["status" => $result ? "success" : "erreur", "message" => $result ? "organisateur modifié avec succes" : "Erreur lors de la modification"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Erreur serveur : " . $e->getMessage()]);
    }
}


    public function deleteOrganisateurById($id_orga,$csrf_token) {
        if(!isset($csrf_token) || $csrf_token!==$_SESSION['csrf_token']){
            return json_encode((["status" => "Erreur","message" => "Token CSRF invalide"]));
        }
        $id_orga=filter_var(trim($id_orga),FILTER_VALIDATE_INT);
        if(!$id_orga){
            return json_encode((["status" => "Erreur","message" => "ID organisateur invalide"]));
        }
        try {
          $orga=$this->organisateurmodel->delete($id_orga);
        if ($orga === "Organisateur supprimee avec succès") {
            echo json_encode([
                
                "status" => "success",
                "message" => " Organisateur supprimé avec succès"
            ]);
        }else{
            echo json_encode([
                "status" => "success",
                // à quoi correspond $orga?
                "message" => "$orga"
        ]);
        }
        }catch(Exception $e){
        echo json_encode(["status" => "error","message" => "Erreur serveur : ".$e->getMessage()]);
    }
}
}