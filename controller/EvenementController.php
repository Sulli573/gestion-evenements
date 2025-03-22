<?php
//code logique, la route va appeler le controller (qui va orchestrer tous les appels necessaire pour fournir au client sa ressource, le model, le template)
require_once __DIR__ . '/../config/sessionManager.php';

class EvenementController{
    //private $userModel; /* car on va utiliser la methode get email */
    private $evenementModel;
    public function __construct($evenementModel){ 
        $this->evenementModel=$evenementModel;
    }

    public function create($data,$csrf_token){
        if(!isset($csrf_token) || $csrf_token!==$_SESSION['csrf_token']){
            return json_encode((["status" => "Erreur","message" => "Token CSRF invalide"]));
        }

        $required_fields=['nom_evenement','date_evenement','description_evenement','place_evenement','type_evenement',
        'id_lieu','heure_debut','heure_fin','prix_evenement'];

        foreach($required_fields as $field){
            if(empty($data[$field])){
                echo json_encode(["status" => "error","message" => "Le champ ".$field." est requis"]);
                return;
            }
        }
        
        $nom=htmlspecialchars(trim($data['nom_evenement']),ENT_QUOTES,'UTF-8');
        $date=htmlspecialchars(trim($data['date_evenement']),ENT_QUOTES,'UTF-8');
        $description=htmlspecialchars(trim($data['description_evenement']),ENT_QUOTES,'UTF-8');
        $eventStart=htmlspecialchars(trim($data['heure_debut']),ENT_QUOTES,'UTF-8');
        $eventEnd=htmlspecialchars(trim($data['heure_fin']),ENT_QUOTES,'UTF-8');
        $prix=htmlspecialchars(trim($data['prix_evenement']),FILTER_VALIDATE_FLOAT);
        $place=htmlspecialchars(trim($data['place_evenement']),ENT_QUOTES,'UTF-8');
        $type=htmlspecialchars(trim($data['type_evenement']),ENT_QUOTES,'UTF-8');
        $id_lieu=htmlspecialchars(trim($data['id_lieu']),FILTER_VALIDATE_INT);
        $id_organisateur=filter_var(trim($data['id_organisateur']),FILTER_VALIDATE_INT);

        if(!$id_lieu){
            return json_encode(["status" => "error","message" => "ID lieu invalide!!"]);
        }

        if(!$id_organisateur){
            return json_encode(["status" => "error","message" => "ID organisateur invalide!!"]);
        }

        try{
            $id_event=$this->evenementModel->create($nom,$date,$description,$place,$type,$id_lieu,$eventStart,$eventEnd,
            $prix,$id_organisateur);

            if(!$id_event){
                return json_encode(["status"=>"error","message"=>"Erreur lors l'ajout d'evenement"]);
            }

            $imagePath = '';
            if (isset($_FILES['image_evenement']) && $_FILES['image_evenement']['error'] == 0) {
                $uploadDir = __DIR__ . '/../assets/images/';
                $fileExtension=strtolower(pathinfo($_FILES['image_evenement']['name'],PATHINFO_EXTENSION));
                $allowedExtensions=['jpg','png','jpeg'];

                if(!in_array($fileExtension,$allowedExtensions)){
                    return json_encode(["status"=>"error","message"=>"Format fichier non autorisé"]);
                }

                $newFileName=$id_event .'.png';
                $imagePath=$uploadDir . $newFileName;

                $imageTmpPath=$_FILES['image_evenement']['tmp_name'];


                if(!$this->convertToPng($imageTmpPath,$imagePath,$fileExtension)){
                    return json_encode(["status"=>"error","message"=>"Erreur lors de la conversion"]);
                }

                $this->evenementModel->updateImage($id_event,$newFileName);
        }
        return json_encode(["status" => "success","message" => "Evenement ajoute avec succes"]);

        }catch(Exception $e){
            echo json_encode(["status" => "error","message" => "Erreur serveur : ".$e->getMessage()]);
        }
    }
    
    public function update($data,$csrf_token){
        if(!isset($csrf_token) || $csrf_token!==$_SESSION['csrf_token']){
            return json_encode((["status" => "Erreur","message" => "Token CSRF invalide"]));
        }

        $required_fields=['nom_evenement','date_evenement','description_evenement','place_evenement','type_evenement',
        'id_lieu','id_evenement','id_organisateur','heure_debut','heure_fin','prix_evenement'];

        foreach($required_fields as $field){
            if(empty($data[$field])){
                echo json_encode(["status" => "error","message" => "Le champ ".$field." est requis"]);
                return;
            }
        }

        $id_evenement=filter_var(trim($data['id_evenement']),FILTER_VALIDATE_INT);
        if(!$id_evenement){
            return json_encode(["status" => "error","message" => "ID evenement invalide!!"]);
        }
        
        $nom=htmlspecialchars(trim($data['nom_evenement']),ENT_QUOTES,'UTF-8');
        $date=htmlspecialchars(trim($data['date_evenement']),ENT_QUOTES,'UTF-8');
        $description=htmlspecialchars(trim($data['description_evenement']),ENT_QUOTES,'UTF-8');
        $place=htmlspecialchars(trim($data['place_evenement']),ENT_QUOTES,'UTF-8');
        $type=htmlspecialchars(trim($data['type_evenement']),ENT_QUOTES,'UTF-8');
        $id_lieu=htmlspecialchars(trim($data['id_lieu']),FILTER_VALIDATE_INT);
        $id_organisateur=htmlspecialchars(trim($data['id_organisateur']),FILTER_VALIDATE_INT);
        $eventStart=htmlspecialchars(trim($data['heure_debut']),ENT_QUOTES,'UTF-8');
        $eventEnd=htmlspecialchars(trim($data['heure_fin']),ENT_QUOTES,'UTF-8');
        $prix=htmlspecialchars(trim($data['prix_evenement']),FILTER_VALIDATE_FLOAT);

        if(!$id_lieu){
            return json_encode(["status" => "error","message" => "ID lieu invalide!!"]);
        }
        
        if(!$id_organisateur){
            return json_encode(["status" => "error","message" => "ID organisateur invalide!!"]);
        }
        $event=$this->evenementModel->getById($id_evenement);
        $registeredImage=$event['image_evenement'];
        if($registeredImage !== $data['image_evenement'] && !$data['image_evenement']){
            $imagePath = '';
            $uploadDir = __DIR__ . '/../assets/images/';
            $newFileName=$id_evenement .'.png';
            $imagePath=$uploadDir . $newFileName;
            unlink($imagePath);
            if (isset($_FILES['image_evenement']) && $_FILES['image_evenement']['error'] == 0) {
                $fileExtension=strtolower(pathinfo($_FILES['image_evenement']['name'],PATHINFO_EXTENSION));
                $allowedExtensions=['jpg','png','jpeg'];

                if(!in_array($fileExtension,$allowedExtensions)){
                    return json_encode(["status"=>"error","message"=>"Format fichier non autorisé"]);
                }

                $imageTmpPath=$_FILES['image_evenement']['tmp_name'];


                if(!$this->convertToPng($imageTmpPath,$imagePath,$fileExtension)){
                    return json_encode(["status"=>"error","message"=>"Erreur lors de la conversion"]);
                }
        }
        }
        try{
            $result=$this->evenementModel->update($id_evenement,$nom,$date,$description,$place,$type,$id_lieu,$id_organisateur,$eventStart,$eventEnd,$prix);
            return json_encode(["status" => $result ? "success":"erreur","message" => $result ? "Evenement modifiee avec succes" : "Erreur lors de la modification"]);
        }catch(Exception $e){
            echo json_encode(["status" => "error","message" => "Erreur serveur : ".$e->getMessage()]);
        }
    }

    public function getAllEvenements(){
        try{
            $events=$this->evenementModel->getAll();
            if(!empty($events)){
                return json_encode([
                    "status" => "Success",
                    "events" => $events
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

    public function getEventById($id_event) {
        if(empty($id_event) || !is_numeric($id_event)) {
            echo json_encode(["status" => "error", "message" =>"Id invalide"]);
            return;
        }try {
            $event=$this->evenementModel->getbyId($id_event);
        if(!empty($event)) {
            return json_encode([
                "status" => "success",
                "event" => $event
            ]);
        }else{
            return json_encode([
                "status" => "success",
                "message" => "Aucun événement trouvé"
            ]);
        }

    }catch(Exception $e) {
        echo json_encode(["status" => "error","message" => "Erreur serveur : ".$e->getMessage()]);
    }
} 

    public function deleteEventById($id_event,$csrf_token) {
        if(!isset($csrf_token) || $csrf_token!==$_SESSION['csrf_token']){
            return json_encode((["status" => "Erreur","message" => "Token CSRF invalide"]));
        }
        
        $id_event=filter_var(trim($id_event),FILTER_VALIDATE_INT);
        if(!$id_event){
            return json_encode(["status" => "error","message" => "ID evenement invalide!!"]);
        }

        try {
          $event=$this->evenementModel->delete($id_event);
        if ($event === "Événement supprimé avec succès") {
            return json_encode([
                "status" => "success",
                "message" => " Événement supprimé avec succès"
            ]);
        }else{
            return json_encode([
                "status" => "success",
                "message" => "$event"
        ]);
    }
    }catch(Exception $e){
        return json_encode(["status" => "error","message" => "Erreur serveur : ".$e->getMessage()]);
    }
    }

    public function getUpcomingEvents(){
        try {
            $events = $this->evenementModel->getUpcomingEvents();
            if (!empty($events)) {
                return json_encode([
                    "status" => "Success",
                    "events" => $events
                ]);
            } else {
                return json_encode([
                    "status" => "Success",
                    "message" => "Aucun événement trouvé"
                ]);
            }
        } catch (Exception $e) {
            return json_encode(["status" => "error", "message" => "Erreur serveur : " . $e->getMessage()]);
        }        
    }

    public function getPassEvents(){
        try{
            $Events=$this->evenementModel->getPassEvents();
            
            if(!empty($Events)){
                return json_encode([
                    "status" => "Succes",
                    "Events" => $Events
                ]);
            }else{
                return json_encode([
                    "status" => "Succes",
                    "message" => "Aucun evenements trouvé"

                ]);
            }
    
        }catch(Exception $e){
        echo json_encode(["status" => "error","message" => "Erreur serveur : ".$e->getMessage()]);
        }
    }  
    
    private function convertToPng($sourcePath,$destinationPath,$extension){
        switch($extension){
            case 'jpeg':
            case 'jpg':
                $image =imagecreatefromjpeg($sourcePath);
                break;
            case 'png':
                return move_uploaded_file($sourcePath,$destinationPath);
            default:
                return false;
        }
        return imagepng($image,$destinationPath);
    }
}

    

?>