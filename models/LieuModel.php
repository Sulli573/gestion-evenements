<?php
require_once "DefaultModel.php";
// is_finish n'apaprait pas car on l'a initialisé à 0 dans la base de données


class LieuModel extends DefaultModel {
    public function create($nom,$lieu){
      
        try{
            $query="INSERT INTO lieu (nom_lieu,adresse_lieu)
                                VALUES(:nom,:adresse)"; 
            $stmt=$this->db->prepare($query);

            $stmt->bindParam(':nom',$nom,PDO::PARAM_STR);
            $stmt->bindParam(':adresse',$lieu,PDO::PARAM_STR);
            if($stmt->execute())
            {
                return true;
            }
            else {
                return 'Erreur lors de la création du lieu';
            }
        }
        catch(PDOException $e){
            return "Erreur SQL: ".$e->getMessage();
        }
    }

    public function getAll(){
        try{
            $query="SELECT id,nom_lieu,adresse_lieu from lieu";
            $stmt=$this->db->prepare($query);
            $stmt->execute();
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }catch(PDOException $e){
            return "Erreur SQL: ".$e->getMessage();
        }
    }

    public function getById($id_lieu){
        try{
            $query="SELECT id,nom_lieu,adresse_lieu from lieu where id=:id_lieu";
            $stmt=$this->db->prepare($query);
            $stmt->bindParam(':id_lieu',$id_lieu,PDO::PARAM_INT);
            $stmt->execute();
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
            if($result) {
                return $result;
            }else {
                return "Aucun lieu trouvé avec cet id";
            }
        }catch(PDOException $e){
            return "Erreur SQL: ".$e->getMessage();
        }
    }

    public function update($id_lieu,$new_nom,$new_adress){
        try{
            $query="UPDATE lieu set nom_lieu=:new_nom, adresse_lieu=:new_adress
                WHERE id=:id_lieu";
                error_log("UPDATE lieu: ID = $id_lieu, Nom = $new_nom, Adresse = $new_adress");
            $stmt=$this->db->prepare($query);
            $stmt->bindParam(':id_lieu',$id_lieu,PDO::PARAM_INT);
            $stmt->bindParam(':new_nom',$new_nom,PDO::PARAM_STR);
            $stmt->bindParam(':new_adress',$new_adress,PDO::PARAM_STR); 
            if($stmt->execute()){
                error_log("Mise à jour réussie !");
                return true;
            }else{
                error_log("Échec de la mise à jour !");
                return 'Erreur lors de la mise a jour du lieu';
            }
        }catch(PDOException $e){
            return "Erreur SQL: ".$e->getMessage();
        }
    }                   

    public function delete($id_lieu) {
        try {
            $query = "DELETE from lieu WHERE id=:id_lieu";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_lieu', $id_lieu, PDO::PARAM_INT);
                if($stmt->execute()) {
                    if($stmt->rowCount()>0)
                    return true;
                }else {
                    echo "Aucun lieu n'a été trouvé";
                    return false;
                }
        }catch (PDOException $e) {
            return "Erreur SQL: " .$e->getMessage();
        }
    }

}
    ?>