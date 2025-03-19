<?php
#class dont les fonctions pourront être utilisées par les autres models
class DefaultModel {
    public PDO $db;
     
    public function __construct() {
        $this->init_bdd();
    }
    public function init_bdd(){

        $database = new DatabaseManager(
            $_ENV['DB_HOST'],
            (int) $_ENV['DB_PORT'],
            $_ENV['DB_NAME'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASS']
        );
        $this->db = $database->connect();
        return $this->db;
    }
}