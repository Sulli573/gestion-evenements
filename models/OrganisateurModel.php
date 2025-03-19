<?php
require_once "DefaultModel.php";
class OrganisateurModel extends DefaultModel {
    public function createOrganisateur($nom,$contact,$email){
        try{
            $verifierRequete="Select id from organisateur where email_organisateur =:email";
            $stmt=$this->db->prepare($verifierRequete);
            $stmt->bindParam(':email',$email,PDO::PARAM_STR);
            $stmt->execute();
            if($stmt->fetch()){
                return 'Cet email existe deja';
            }

            $query="Insert into organisateur (nom_organisateur, contact_organisateur, email_organisateur)
                    VALUES(:nom,:contact,:email)";
            $stmt=$this->db->prepare($query);
            $stmt->bindParam(':nom',$nom,PDO::PARAM_STR);
            $stmt->bindParam(':contact',$contact,PDO::PARAM_STR);
            $stmt->bindParam(':email',$email,PDO::PARAM_STR);
            if($stmt->execute()){
                return true;
            }else{
                return 'Erreur lors de l\'insertion';
            }
        }catch(PDOException $e){
            return "Erreur SQL: ".$e->getMessage();
        }
    }

    public function getOrganisaByEmail($email){
        $stmt=$this->db->prepare('select * from organisateur where email_organisateur = :email_organisateur');/* email correspond à l'email que l'utilisateur va entrer dans le champ*/
        $stmt->execute(['email_organisateur' => $email]);
        return $stmt->fetch();
    }

    public function update($id_orga, $new_nom, $new_contact, $new_email){
    
        try{
        $query="UPDATE organisateur set nom_organisateur =:new_nom,contact_organisateur =:new_contact, email_organisateur=:new_email WHERE id =:id_orga";
        
        $stmt=$this->db->prepare($query);
        $stmt->bindParam(':id_orga',$id_orga,PDO::PARAM_INT);
        $stmt->bindParam(':new_nom',$new_nom,PDO::PARAM_STR);
        $stmt->bindParam(':new_email',$new_email,PDO::PARAM_STR);
        $stmt->bindParam(':new_contact',$new_contact,PDO::PARAM_STR);
        
        if($stmt->execute()){
            return true;
        }else{
            return 'Erreur lors de la mise à jour';
        }
    }catch(PDOException $e){
        return "Erreur SQL: ".$e->getMessage();
        }

    }
    
    public function getAll(){
        try{
            $query="SELECT id, nom_organisateur, contact_organisateur, email_organisateur from organisateur";
            $stmt=$this->db->prepare($query);
            $stmt->execute();
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }catch(PDOException $e){
            return "Erreur SQL: ".$e->getMessage();
        }
    }

    public function getById($id_orga){
        try{
            $query="SELECT * from organisateur WHERE id=:id_orga"; #On va utiliser l'id de l'utilisateur($id_user) stockée dans la session pour afficher ses données
            $stmt=$this->db->prepare($query);
            $stmt->bindParam(':id_orga',$id_orga,PDO::PARAM_INT);
            $stmt->execute();
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC); #recupération des données dans $result sous forme de tableau clé => valeur
            if($result){
                return $result;
            }else{
                return "Pad d'organisateur trouvé"; 
            }
        }catch(PDOException $e){
            return "Erreur SQL: ".$e->getMessage();
        }
    }

    public function delete($id_orga){
        try{
            $query="DELETE from organisateur WHERE id=:id_orga";
            $stmt=$this->db->prepare($query);
            $stmt->bindParam(':id_orga',$id_orga,PDO::PARAM_INT);
            #Si la requète delete est executée et qu'il y a plus de 0 ligne éffacée (donc que l'organisateur a bien été trouvé et supprimé)
            if($stmt->execute()){
                if($stmt->rowCount()>0){
                    return "Organisateur supprimé avec succès";
                }else{
                    return "Aucun organisateur est trouvé avec cet ID"; 

                }
            }
        }catch(PDOException $e){
            return "Erreur SQL: ".$e->getMessage();
        }
    }
}