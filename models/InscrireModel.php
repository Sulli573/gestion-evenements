<?php
require_once "DefaultModel.php";
require_once "EvenementsModel.php";

class InscrireModel extends DefaultModel {
    // private $EvenementsModel;

    // public function __construct($EvenementsModel)
    // {
    //     $this->EvenementsModel=$EvenementsModel;
    // }

    public function create($id_utilisateur,$id_evenement,$nbr_ticket){
        try{
            $eventQuery="SELECT place_restantes FROM evenements WHERE id_evenement=:id_event";
            $stmt=$this->db->prepare($eventQuery);
            $stmt->bindParam(':id_event',$id_evenement,PDO::PARAM_INT);
            $stmt->execute();
            $data=$stmt->fetch(PDO::FETCH_ASSOC);
            $place_restante = (int)$data['place_restantes'];
                if ($place_restante < $nbr_ticket) {
                    return 'Nombre de tickets libres insuffisant';
                }

            $query="INSERT INTO inscrire (id_utilisateur ,id_evenement ,date_inscription,nbr_ticket) 
                    VALUES(:id_user,:id_event,:date_inscription,:nbr_ticket)";
            $stmt=$this->db->prepare($query);
            $date_inscription=date('Y-m-d H:i:s');
            $stmt->bindParam(':id_user',$id_utilisateur,PDO::PARAM_INT);
            $stmt->bindParam(':id_event',$id_evenement,PDO::PARAM_INT);
            $stmt->bindParam(':date_inscription',$date_inscription,PDO::PARAM_STR);
            $stmt->bindParam(':nbr_ticket',$nbr_ticket,PDO::PARAM_INT);
            if($stmt->execute()) {
                return true;
            }else{
                return 'Erreur lors de l\'insertion';
            }
        }catch(PDOException $e){
            return "Erreur SQL: ".$e->getMessage();
        }
    }

    public function retirerInscription($id_user,$id_evenement){
        try{
            $get_place="SELECT place_restantes from evenements where id_evenement=:id";
            $stmt=$this->db->prepare($get_place);
            $stmt->bindParam(':id',$id_evenement,PDO::PARAM_INT);
            $stmt->execute();
            $result=$stmt->fetch(PDO::FETCH_ASSOC);

            if($result===false){
                return "evenement introuvable";
            }
            
            $place_restante=$result['place_restantes']+1;
            $query="UPDATE evenements set place_restantes=:place_rest where id_evenement=:id_evenement";
            $stmt=$this->db->prepare($query);
            $stmt->bindParam(':place_rest',$place_restante,PDO::PARAM_INT);
            $stmt->bindParam(':id_evenement',$id_evenement,PDO::PARAM_INT);
            if($stmt->execute()){
                $query="DELETE FROM inscrire where id_evenement=:id_evenement and id_utilisateur=:id_user";
                $stmt=$this->db->prepare($query);
                $stmt->bindParam(':id_user',$id_user,PDO::PARAM_INT);
                $stmt->bindParam(':id_evenement',$id_evenement,PDO::PARAM_INT);
                if($stmt->execute()){
                    if($stmt->rowCount()>0){
                        return true;
                    }else{
                        return "Aucune inscription n'est trouvé avec cet ID"; 
                    }
                }
            }else{
                return 'Erreur lors de l\'update';
            }
        }catch(PDOException $e){
            return "Erreur SQL: ".$e->getMessage();
        }
    }
    public function afficherInscriptionByUserId($userId) {
        $sql="SELECT i.code,i.id_utilisateur,i.id_evenement,i.date_inscription,SUM(i.nbr_ticket) as nbr_ticket,
        e.nom_evenement,e.date_evenement,e.description_evenement,e.place_restantes,e.prix_evenement,e.image_evenement,
        l.nom_lieu FROM inscrire i
            join evenements e on i.id_evenement=e.id_evenement 
            JOIN lieu l on l.id=e.id_lieu  
            WHERE id_utilisateur=:userId AND e.date_evenement >= NOW()";
        $stmt=$this->db->prepare($sql);
        $stmt->bindParam(':userId',$userId,PDO::PARAM_INT);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)) {
            return $result;
        }else{
            return "Aucun événement futur n'a été trouvé";
        }
    }
}