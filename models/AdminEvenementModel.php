<?php
    
    class UserEvenementModel{
        private $db;
        private $id_evenement;
        private $nom_evenement;
        private $date_evenement;
        private $description_evenement;
        private $place_evenement;
        private $image_evenement;
        private $type_evenement;
        private $is_finish;
        private $id_organisateur;
        private $id_lieu;
            

        public function __construct($db){
            $this->db=$db;
        }
      
        public function CreerunEvenement($nom,$date){

            //soit créer lieu et evenement 
            //soit recupérer l'id du lieu et créer avec un evenement

            //faire une fonction qui va créer un evenement avec l'id
            //faire une fonction qui va créer un evenement avec la donnée
            //une fonction qui va appeler l'un ou l'autre en fonction du besoin (en fonction de ce quelle a en parametre)
            //vérifier que les données soient bien saisies (bon format, pas injection sql ...)
        }
        public function createEvent($nom,$date){
           try{
                $query="INSERT INTO evenements (nom_evenement,date_evenement) VALUES(:nom,:date)"; 
                $stmt=$this->db->prepare($query);
                return $stmt->execute([
                    'nom' => $nom,
                    'date' => $date
                ]);
           }
           catch(PDOException $e) {
                echo 'Problème de base de données' . $e->getMessage();
                return false;
            }
        }
        public function modifierUnEvenement(){
            //dont Organisateur
        }

        public function modifierUnOrganiser(){
            //un peu pareil que creerunevenement

        }
    }
?>

