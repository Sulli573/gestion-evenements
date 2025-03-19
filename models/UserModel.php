
<?php
    require_once "DefaultModel.php";
    class UserModel extends DefaultModel {//création d'une classe + instance    
        public function createUser($nom,$email,$mot_de_passe){
           try{
                $query="INSERT INTO utilisateur (nom_utilisateur,courriel_utilisateur,mot_de_passe_utilisateur) VALUES(:nom,:email,:mot_de_passe)"; 
                $stmt= $this->db->prepare($query);
                return $stmt->execute([
                    'nom' => $nom,
                    'email' => $email,
                    'mot_de_passe' => $mot_de_passe
                ]);

           }catch(PDOException $e){
                echo 'Problème de base de données' . $e->getMessage();
                return false;
           }
        }
        
        public function getUserByEmail($email){
            /*stmt pour rpéparer une requete */
            $stmt=$this->db->prepare('select * from utilisateur where courriel_utilisateur = :courriel_utilisateur');/* email correspond à l'email que l'utilisateur va entrer dans le champ*/
            $stmt->execute(['courriel_utilisateur' => $email]);
            return $stmt->fetch();
        }

        public function update($id_user, $nom, $email, $role, $is_suspended, $is_active, $motif_suspension){
        try {
            $query = "UPDATE utilisateur 
                      SET nom_utilisateur = :nom, courriel_utilisateur = :email, role_utilisateur = :role,
                          is_suspended = :is_suspended, is_active = :is_active, motif_suspension = :motif_suspension
                      WHERE id_utilisateur = :id_user";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                'id_user' => $id_user,
                'nom' => $nom,
                'email' => $email,
                'role' => $role,
                'is_suspended' => $is_suspended,
                'is_active' => $is_active,
                'motif_suspension' => $motif_suspension
            ]);
        } catch(PDOException $e) {
            return "Erreur SQL: " . $e->getMessage();
        }
    }

        public function getAll(){
            try{
                $query="SELECT id_utilisateur,nom_utilisateur,courriel_utilisateur,role_utilisateur,is_suspended,is_active,motif_suspension
                        from utilisateur";
                $stmt=$this->db->prepare($query);
                $stmt->execute();
                $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }catch(PDOException $e){
                return "Erreur SQL: ".$e->getMessage();
            }
        }

        public function getById($id_user){
            try{
                $query="SELECT id_utilisateur,nom_utilisateur,courriel_utilisateur,role_utilisateur,is_suspended,is_active,motif_suspension
                        from utilisateur WHERE id_utilisateur=:id_user";
                $stmt=$this->db->prepare($query);
                $stmt->bindParam(':id_user',$id_user,PDO::PARAM_INT);
                $stmt->execute();
                $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if($result){
                    return $result;
                }else{
                    return "Aucune inscription trouvee pour cette utilisateur"; 
                }
            }catch(PDOException $e){
                return "Erreur SQL: ".$e->getMessage();
            }
        }

        public function delete($id_user){
            try{
                $query="DELETE from utilisateur WHERE id_utilisateur=:id_user";
                $stmt=$this->db->prepare($query);
                $stmt->bindParam(':id_user',$id_user,PDO::PARAM_INT);
                if($stmt->execute()){
                    if($stmt->rowCount()>0){
                        return "Utilisateur supprimee avec succes";
                    }else{
                        return "Aucun utilisateur est trouvee par cette ID"; 
                    }
                }
            }catch(PDOException $e){
                return "Erreur SQL: ".$e->getMessage();
            }
        }
    } 

    ?>