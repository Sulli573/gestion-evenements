<?php
require_once "DefaultModel.php";

class EvenementsModel extends DefaultModel {
    public function create($nom,$date,$description,$place,$image,$type_event,$id_lieu,$heureDebut,$heureFin,$prix){
        try{
            $query="INSERT INTO evenements (nom_evenement,date_evenement,description_evenement,place_evenement,
                    place_restantes,image_evenement,type_evenement,id_lieu,heure_debut,heure_fin,prix_evenement)
                    VALUES(:nom,:date_event,:description_event,:place,:place_rest,:images,:types,:lieu,:heure_debut,:heure_fin,:prix_evenement)";
            $stmt=$this->db->prepare($query);
            $stmt->bindParam(':nom',$nom,PDO::PARAM_STR);
            $stmt->bindParam(':date_event',$date,PDO::PARAM_STR);
            $stmt->bindParam(':description_event',$description,PDO::PARAM_STR);
            $stmt->bindParam(':place',$place,PDO::PARAM_STR);
            $stmt->bindParam(':place_rest',$place,PDO::PARAM_STR);
            $stmt->bindParam(':images',$image,PDO::PARAM_STR);
            $stmt->bindParam(':types',$type_event,PDO::PARAM_STR);
            $stmt->bindParam(':lieu',$id_lieu,PDO::PARAM_STR);
            $stmt->bindParam(':heure_debut',$heureDebut,PDO::PARAM_STR);
            $stmt->bindParam(':heure_fin',$heureFin,PDO::PARAM_STR);
            $stmt->bindParam(':prix_evenement',$prix,PDO::PARAM_STR);
            if($stmt->execute()){
                return true;
            }else{
                return 'Erreur lors de l\'insertion';
            }
        }catch(PDOException $e){
            return "Erreur SQL: ".$e->getMessage();
        }
    }

    public function update($id,$nom,$date,$description,$place,$type_event,$id_lieu,$organisateur,$heureDebut,$heureFin,$prix){
        try{
            $get_place="SELECT place_evenement,place_restantes from evenements where id_evenement=:id";
            $stmt=$this->db->prepare($get_place);
            $stmt->bindParam(':id',$id,PDO::PARAM_STR);
            $stmt->execute();
            $data=$stmt->fetch(PDO::FETCH_ASSOC);
            $old_place=(int)$data['place_evenement'];
            $place_restante=(int)$data['place_restantes'];
            $place_ajoute=0;
            //old_place=50
            //place=30
            //place_ajoute=30-50=-20
              //place_restante=10
            //place_restante=10-20=-10
            if($old_place < $place){
                $place_ajoute = $place - $old_place;
                $place_restante = $place_restante + $place_ajoute;
                $place = $place + $place_ajoute;
            }
            elseif($old_place > $place) {
                $placeEnleve = $old_place - $place;
                if($place_restante < $placeEnleve) {
                    echo json_encode(["status" => "error","message" => "T'as eliminé meme les places réservées => opération interdite"]);
                    return;
                }
                $place_restante=$place_restante - $placeEnleve;
            }
            
            $query="UPDATE evenements set nom_evenement=:nom,date_evenement=:date_event,
            description_evenement=:description_event,place_evenement=:place,place_restantes=:place_rest,
            type_evenement=:types_event,id_lieu=:lieu,id_organisateur=:organisateur,heure_debut=:heure_debut,heure_fin=:heure_fin,prix_evenement=:prix
            WHERE id_evenement=:id";
            $stmt=$this->db->prepare($query);
            $stmt->bindParam(':nom',$nom,PDO::PARAM_STR);
            $stmt->bindParam(':date_event',$date,PDO::PARAM_STR);
            $stmt->bindParam(':description_event',$description,PDO::PARAM_STR);
            $stmt->bindParam(':place',$place,PDO::PARAM_INT);
            $stmt->bindParam(':place_rest',$place_restante,PDO::PARAM_INT);
            // $stmt->bindParam(':images',$image,PDO::PARAM_STR);
            $stmt->bindParam(':types_event',$type_event,PDO::PARAM_STR);
            $stmt->bindParam(':lieu',$id_lieu,PDO::PARAM_STR);
            $stmt->bindParam(':id',$id,PDO::PARAM_STR);
            $stmt->bindParam(':organisateur',$organisateur,PDO::PARAM_STR);
            $stmt->bindParam(':heure_debut',$heureDebut,PDO::PARAM_STR);
            $stmt->bindParam(':heure_fin',$heureFin,PDO::PARAM_STR);
            $stmt->bindParam(':prix',$prix,PDO::PARAM_STR);
            if($stmt->execute()){
                return true;
            }else{
                return 'Erreur lors de l\'insertion';
            }
        }catch(PDOException $e){
            return "Erreur SQL: ".$e->getMessage();
        }

        //place=100 place_rest=40 si il essaie de change place=200 place_rest=140
        //les_place_ajoute=new_place-old_place=200-100=100
        //place_restantes=les_place_ajoute+place_restantes
    }
    // public function selectEvenement() {
    //     $query="SELECT * FROM `evenements` order by id desc LIMIT 5"; //Sélectionne les 5 derniers concerts.
    //     $stmt=$this->db->prepare($query);    
    //     return $stmt->execute();
    // }

    // public function afficheInfoEvenement($id): mixed{
    //     $query=("SELECT * FROM evenements WHERE id = :id"); // affiche toutes les informations d'un événement selectionné par son id. prepare pour faire requête en 2 temps quand on a pas confiance (exemple qu'un utilisateur peut faire de l'injection SQL à partir d'un champ de saisie )
       
    //     $stmt=$this->db->prepare($query);
       
    //     //$stmt->bindParam(':id', $id, PDO::PARAM_STR); meme chose que execute, ça link la donnée préparée
    //     $stmt->execute([
    //         ":id" => $id
    //     ]);
    //     return $stmt->fetch(); // fetch permet d'afficher la requète (sous forme de tableau associatif) Attention fetch ne fourni qu'une seule ligne, fetch all permet de récupéer toutes les lignes que fourni la requète
        
    // }   
    // public function EvenementParOrganisateur() {
    //     $query="SELECT nom_evenement,nom_organisateur FROM evenements JOIN organisateur ON evenements.id_organisateur=organisateur.id"; // 
    // }

    public function getAll(){
        try{
            $query="SELECT e.*, l.nom_lieu ,o.nom_organisateur FROM evenements e 
                    JOIN lieu l ON e.id_lieu = l.id 
                    JOIN organisateur o ON e.id_organisateur=o.id";
            $stmt=$this->db->prepare($query);
            $stmt->execute();
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }catch(PDOException $e){
            return "Erreur SQL: ".$e->getMessage();
        }
    }
    
    public function getById($id_event) { //Pour la card d'un evenement pour afficher les détail sur une autre page. 
        try {
            $query = "SELECT * from evenements WHERE id_evenement = :id_event";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_event',$id_event, PDO::PARAM_INT);
            $stmt->execute();
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if($result) {
                    return $result;
                }else {
                    return "Aucun événement trouvé avec cet id";
                }
        }catch(PDOException $e) {
            return "Erreur SQL: ".$e->getMessage();
        }

    }

    public function delete($id_event) {
        try{
            $query = "DELETE from evenements WHERE id_evenement=:id_event";
            $stmt=$this->db->prepare($query);
            $stmt->bindParam('id_event',$id_event,PDO::PARAM_INT);
        #Si la requète delete est executée et qu'il y a plus de 0 ligne éffacée (donc que l'évenment a bien été trouvé et supprimé)
        if($stmt->execute()){
            if($stmt->rowCount()>0){
                return "Evenement supprimé avec succès";
            }else{
                return "Aucun Evenement est trouvé avec cet ID"; 
            }
        }
        }catch(PDOException $e){
            return "Erreur SQL: ".$e->getMessage();
        }
    }
    
    public function getUpcomingEvents(){
        try{
            $query="SELECT e.*, l.nom_lieu FROM evenements e JOIN lieu l ON e.id_lieu = l.id 
                    WHERE e.date_evenement >= CURDATE() ORDER BY e.date_evenement ASC";
            $stmt=$this->db->prepare($query);
            $stmt->execute();
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($result)){
                return $result;
            }else{
                return "Aucun evenement futur n'est trouvé"; 
            }
        }catch(PDOException $e){
            return "Erreur SQL: ".$e->getMessage();
        }
    }

    public function getPassEvents() {
        try{
            $query = "SELECT e.*, l.nom_lieu FROM evenements e JOIN lieu l ON e.id_lieu = l.id 
                      WHERE e.date_evenement < CURDATE() ORDER BY e.date_evenement ASC";

            $stmt=$this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($result)){
                return $result;
            }else {
                return "Aucun événement passé n'est trouvé";
            }
        }catch(PDOException $e){
            return "Erreur SQL: ".$e->getMessage();
        }
    }
}

?>